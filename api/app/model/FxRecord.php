<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/1 0001
 * Time: 9:49
 */

namespace app\model;


use app\services\CpyPayUserService;
use bases\BaseCommon;
use bases\BaseModel;
use function PHPSTORM_META\elementType;
use think\facade\Log;

class FxRecord extends BaseModel
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,headpic,mobile');
    }

    public function agent()
    {
        return $this->belongsTo('User', 'agent_id', 'id')
            ->field('id,openid,openid_gzh,openid_app,nickname,user_name,unionid,headpic,mobile');
    }

    /**
     * 用户查看分销收入统计
     * @param $agent_id
     * @return mixed
     */
    public static function getFxMoneyData($agent_id)
    {
        $data['money'] = self::where('agent_id', $agent_id)->where('status', 0)->count('money');
        $data['yesterday_money'] = self::where('agent_id', $agent_id)->where('status', 0)
            ->whereDay('create_time', 'yesterday')
            ->count('money');
        return app('json')->success($data);
    }

    /**
     * 用户获取分销记录
     * @param $agent_id
     * @return mixed
     */
    public static function userGetRecord($agent_id)
    {
        $data = self::with(['user'])->where('agent_id', $agent_id)->select();
        return app('json')->success($data);
    }

    /**
     * 查看排名
     * @param $agent_id
     * @return mixed
     */
    public static function getFxRank($agent_id)
    {
        $data['rank'] = self::with(['agent'])
            ->field('id,agent_id,sum(money) as total_money')
            ->group('agent_id')
            ->order('total_money', 'desc')
            ->select()
            ->toArray();
        foreach ($data['rank'] as $k => $v) {
            $data['self'] = ['rank' => 0, 'total_money' => 0];
            if ($agent_id == $v['agent_id']) {
                $data['self'] = ['rank' => $k + 1, 'total_money' => $v['total_money']];
            }
        }
        return app('json')->success($data);
    }

    /**
     * cms获取分销记录
     * @return mixed
     */
    public static function getRecord()
    {
        $data = self::with(['agent', 'user'])->order('id','desc')->select();
        return app('json')->success($data);
    }

    /**
     * 创建商品分销提现记录
     * @param $oid
     * @param $uid
     * @return mixed
     */
    public static function addRecord($oid, $uid)
    {
        $state = app('system')->getValue('fx_status');//是否开启分销
        $goods_status = app('system')->getValue('goods_fx_status');
        if ($state == 0 || $goods_status != 1) {
            return app('json')->fail();
        }
        $order = Order::with('ordergoods')->where('order_id', $oid)->find();
        //1推荐人分销2人人分销
        if ($state == 1) {
            $pid = FxAgent::where('invite_code', $order['invite_code'])->value('user_id');
        } else if ($state == 2) {
            $pid = User::where('invite_code', $order['invite_code'])->value('id');
        } else {
            return app('json')->fail();
        }
        if (!$pid || $uid == $pid) {
            return app('json')->fail();
        }
        foreach ($order['ordergoods'] as $k => $v) {
            $goods = FxGoods::where('goods_id', $v['goods_id'])->find();
            if ($goods) {
                $data = self::setData($pid, $oid, $uid, $goods['price'], 2);
                $record=self::create($data);
                $royalty = app('system')->getValue('fx_royalty');//是否开启自动提现功能
                if($royalty==1){
                    self::autoTx($record['id'],'分销佣金');
                }
            }
        }
    }

    /**
     * 创建VIP提现记录
     * @param $oid
     * @param $uid
     * @return mixed
     */
    public static function addVipRecord($oid, $uid)
    {
        $state = app('system')->getValue('fx_status');//是否开启分销
        if ($state == 0) {
            return app('json')->fail();
        }
        $goods_status = app('system')->getValue('vip_fx_status');
        if ($goods_status != 1) {
            return app('json')->fail();
        }
        $res = FxBind::where('user_id', $uid)->where('is_bind', 1)->find();//有没有上线
        if (!$res) {
            return app('json')->fail();
        }

        $price = app('system')->getValue('agent_tc');
        $data = self::setData($res['pid'], $oid, $uid, $price, 1);
        $record=self::create($data);
        $royalty = app('system')->getValue('fx_royalty');//是否开启自动提现功能
        if($royalty==1){
            self::autoTx($record['id'],'代理商分销提成');
        }
    }

    private static function setData($agent_id, $oid, $uid, $price, $type)
    {
        $data['order_num'] = (new BaseCommon())->makeOrderNum();
        $data['order_id'] = $oid;
        $data['user_id'] = $uid;
        $data['agent_id'] = $agent_id;
        $data['money'] = $price;
        $data['status'] = 0;
        $data['type'] = $type;
        return $data;
    }

    /**
     * 用户手动申请提现
     * @param $uid
     * @param $ids
     * @return mixed
     */
    public static function userApplyTx($uid,$ids){
        $royalty = app('system')->getValue('fx_royalty');//是否开启自动提现功能
        if($royalty!=0){
            return app('json')->fail('未开启手动提现');
        }
        foreach ($ids as $k=>$v){
            self::where('id',$v)->where('status',0)->where('agent_id',$uid)->update(['status'=>1]);
        }
        return app('json')->success();
    }

    /**
     * cms打款成功
     * @param $id
     * @return mixed
     */
    public static function agreeTxApply($id)
    {
        self::where(['id'=>$id,'status'=>1])->update(['status'=>2]);
        return app('json')->success();
    }

    /**
     * 自动提现
     * @param $id
     * @param $msg
     * @return string|\think\response\Json
     */
    public static function autoTx($id,$msg){
        $record=new self();
        $res=$record->with('agent')->where('id',$id)->find();
        $pay_num=$record->where('agent_id',$id)->whereTime('pay_time','today')->count();
        if($pay_num>8){
            $res->save(['cpy_pay_status'=>3]);
            return '';
        }
        $data['order_sn']=$res['order_num'];
        $data['openid']=$res['agent']['openid'];
        $data['truename']=$res['agent']['nickname'];
        $data['money']=$res['money'];	//最小3角
        $data['desc']=$msg;
        $pay=new CpyPayUserService();
        $cpyPay=$pay->transfer($data);
        if($cpyPay['code']){
            $res->save(['status'=>2,'cpy_pay_status'=>1,'wx_pay'=>$cpyPay['data']['payment_no'],'pay_time'=>$cpyPay['data']['payment_time']]);
        }else{
            $res->save(['cpy_pay_status'=>2]);
            Log::record($cpyPay,'error');
        }
        return app('json')->success();
    }

    /**
     * 统计未打款的，统一打款
     * @return mixed
     */
    public function taskTx(){
        $royalty = app('system')->getValue('fx_royalty');//是否开启自动提现功能
        if($royalty!=1){
            return app('json')->fail('未开启自动提现');
        }
        $record=new self();
        $res=$record->with('agent')->where('cpy_pay_status',3)->select();
        $arr=[];
        foreach ($res as $k=>$v){
            if(!$arr){
                $data['ids']=[];
                array_push($data['ids'],$v['id']);
                $data['order_sn']=$v['order_num'];
                $data['agent_id']=$v['agent_id'];
                $data['openid']=$v['agent']['openid'];
                $data['truename']=$v['agent']['nickname'];
                $data['money']=$v['money'];	//最小3角
                $data['desc']='代理商分销提成';
                array_push($arr,$data);
            }else{
                foreach ($arr as $kk=>$vv){
                    if($v['agent_id']==$vv['agent_id']){
                        array_push($vv['ids'],$v['id']);
                        $vv['money']+=$v['money'];	//最小3角
                    }else{
                        $data['ids']=[];
                        array_push($data['ids'],$v['id']);
                        $data['order_sn']=$v['order_num'];
                        $data['agent_id']=$v['agent_id'];
                        $data['openid']=$v['agent']['openid'];
                        $data['truename']=$v['agent']['nickname'];
                        $data['money']=$v['money'];	//最小3角
                        $data['desc']='代理商分销提成';
                        array_push($arr,$data);
                    }
                }
            }
        }
        $pay=new CpyPayUserService();
        foreach ($arr as $k=>$v){
            $cpyPay=$pay->transfer($v);
            if($cpyPay['code']){
                $record->where(['id'=>$v['ids']])->update(['status'=>2,'cpy_pay_status'=>1,'wx_pay'=>$cpyPay['data']['payment_no'],'pay_time'=>strtotime($cpyPay['data']['payment_time'])]);
            }else{
                $record->where(['id'=>$v['ids']])->update(['cpy_pay_status'=>2]);
                Log::record($cpyPay,'error');
            }
        }
    }

}