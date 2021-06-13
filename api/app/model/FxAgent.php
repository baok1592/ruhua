<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31 0031
 * Time: 15:39
 */

namespace app\model;


use bases\BaseCommon;
use bases\BaseModel;
use think\facade\Db;

class FxAgent extends BaseModel
{

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,headpic,mobile');
    }

    /**
     * 成为代理商
     * @param $uid
     * @param $sfm
     * @return mixed
     */
    public static function addAgent($uid,$sfm)
    {
        $state = SysConfig::where(['key' => 'fx_status'])->value('value');
        if ($state != 1 && $state != 2) {
            return app('json')->fail('分销未开启');
        }
        $res=self::where('user_id',$uid)->find();
        if($res){
            return app('json')->fail('已成为分销商');
        }
        $money = SysConfig::where(['key' => 'agent_money'])->value('value');
        if ($money == 0) {
            $invite_code = User::where(['user_id' => $uid])->value('invite_code');
            $res = self::create(['user_id' => $uid, 'invite_code' => $invite_code]);
            FxBind::addBind($sfm,$uid,1);  //添加绑定，直接永久绑定
            return app('json')->success($res);
        }
        $data['order_num'] = (new BaseCommon())->makeOrderNum();
        $data['user_id'] = $uid;
        $data['pay_money'] = $money;
        $data['order_statue'] = 1;
        $res = FxOrder::create($data);
        FxBind::addBind($sfm,$uid,0);   //未支付，临时添加绑定,支付成功永久绑定
        return app('json')->success($res);
    }

    /**
     * 购买成功支付回调
     * @param $id
     * @param $prepay
     * @return mixed
     */
    public static function notifyAgent($id, $prepay)
    {
        Db::startTrans();
        try {
            $order = FxOrder::where('id', $id)->find();
            $order['order_statue'] = 2;
            $order['pay_time'] = time();
            $order['prepay_id'] = $prepay;
            $order->save();
            $invite_code = User::where(['user_id' => $order['user_id']])->value('invite_code');
            self::create(['user_id' => $order['user_id'], 'invite_code' => $invite_code]);
            FxBind::editBind($order['user_id']);       //支付成功确认綁定
            event("AddVipFxRecord", [$id, $order['user_id']]);//分销
            return app('json')->success();
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail();
        }
    }
}