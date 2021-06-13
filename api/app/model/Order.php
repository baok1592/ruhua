<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 9:06
 */

namespace app\model;


use app\services\TokenService;
use app\services\TuiService;
use bases\BaseModel;
use exceptions\OrderException;
use think\facade\Db;
use think\model\concern\SoftDelete;
use think\Request;

class Order extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden = ['delete_time'];

    /**
     * 用户删除订单
     * @param $uid
     * @param $id
     * @return int
     */
    public static function deleteOrder($id, $uid)
    {
        $order = self::where(['order_id' => $id])->find();
        if ($order['user_id'] == $uid && $order['payment_state'] == 0) {
            $order->delete(config('setting.soft_del'));   //这里是软删除
        } else {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 用户提交评价
     * @param $uid
     * @param $post
     * @return mixed
     * @throws
     */
    public static function setPj($uid, $post)
    {
        $where['user_id'] = $uid;
        $where['order_id'] = $post['id'];
        $order = self::where($where)->where(['state' => 1])->find();
        if (!$order) {
            return app('json')->fail('评价出现错误');
        }
        $orderGoods = new OrderGoods();
        $order_id = $order['order_id'];
        $goods_ids = $orderGoods->where('order_id', $order_id)->column('goods_id');
        if (in_array($post['goods_id'], $goods_ids)) {
            $data['user_id'] = $where['user_id'];
            $data['rate'] = $post['rate'];
            $data['content'] = $post['content'];
            $data['order_id'] = $order_id;
            $data['goods_id'] = $post['goods_id'];
            $data['imgs'] = implode(',',$post['imgs']);
            $data['create_time'] = time();
        }
        Db::startTrans();
        try {
            OrderGoods::where(['order_id' => $post['id'], 'user_id' => $uid, 'goods_id' => $post['goods_id']])->update(['state' => 2]);
            $status=OrderGoods::Where(['order_id' => $post['id'],'state'=>1])->find();
            if(!$status){
                $order->save(['state' => 2]);
            }
            Rate::create($data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 确认收货
     * @param $id
     * @param $uid
     * @return mixed
     * @throws
     */
    public static function receive($id, $uid)
    {
        $where['shipment_state'] = 1;
        $where['payment_state'] = 1;
        $where['state'] = 0;
        $where['order_id'] = $id;
        $where['user_id'] = $uid;
        $order = self::where($where)->find();
        if (!$order) {
            return app('json')->fail('订单状态有误');
        }
        Db::startTrans();
        try {
            $res = $order->save(['state' => 1, 'shipment_state' => 2]);
            OrderGoods::where(['order_id' => $id, 'user_id' => $uid])->update(['state' => 1]);
            event('EndOrder', $order);//订单完成监听
            event("AddGoodsFxRecord", [$id, $uid]);//分销订阅
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new OrderException(['msg' => $e->getMessage()]);
        }
        if ($res) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 申请退款
     * @param $uid
     * @param $post
     * @return mixed
     * @throws
     */
    public function tuikuan_approve($uid, $post)
    {
        $money=$this->getTuiMoney($uid,$post['order_id'],$post['goods_id']);
        if(!is_numeric($money)){
            return app('json')->fail($money);
        }
        $user = User::find($uid);
        $order = self::where('order_id',$post['order_id'])->find();
        $data['order_id'] = $order['order_id'];
        $data['nickname'] = $user['nickname'];
        $data['order_num'] = $order['order_num'];
        $data['money'] =$money;
        $data['because'] = $post['radio'] ?: "";
        $data['message'] = $post['content'] ?: "";
        $data['goods_id'] = $post['goods_id'] ?$post['goods_id']:0;
        $data['ip'] =  $data['user_ip'] = (new Request())->ip(); //买家IP
        Db::startTrans();

        try {
            if($data['goods_id']==0){
                $order->save(['state' => -1]);
            }else{
                $goodsWhere['goods_id']=$post['goods_id'];
            }
            $goodsWhere['order_id']=$post['order_id'];
            $goodsWhere['user_id']=$uid;
            OrderGoods::where($goodsWhere)->update(['state' => -1]);
            $tui = Tui::create($data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new OrderException(['msg' => '更新失败']);
        }
        if ($tui) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 获取退款金额
     * @param $uid
     * @param $order_id
     * @param $goods_id
     * @return int|mixed
     */
    public function getTuiMoney($uid,$order_id,$goods_id){
        $tui_state = Tui::where(['order_id' => $order_id,'goods_id'=>$goods_id, 'status' => ['>', 0]])->find();
        if ($tui_state) {
            return '已存在正在处理的退款申请';
        }
        $where['user_id'] = $uid;
        $where['order_id'] = $order_id;
        $where['payment_state'] = 1;
        $where['shipment_state'] = 0;
        $where['state'] = 0;
        $order = self::with('ordergoods')->where($where)->find();
        if (!$order) {
            return '订单状态有误';
        }
        if($order['item_id']){
            $pt=PtItem::where('id',$order['item_id'])->find();
            if($pt['state']!=2||$pt['state']!=-1){
                return '拼团中，请等待拼团结束';
            }
        }
        $total_money=0;
        if($goods_id==0){
            $apply_money=Tui::where('order_id',$order_id)->sum('money');
            $total_money=$order['order_money']-$apply_money;
        }else{
            foreach ($order['order_goods'] as $k => $v) {
                if ($goods_id == $v['goods_id']) {
                    $total_money = $v['total_price'];
                }
            }
            if ($order['coupon_id']) {
                $coupon = UserCoupon::where('id', $order['coupon_id'])->find();
                if ($coupon && ($order['order_money'] - $total_money) < $coupon['full']) {
                    $total_money = $total_money - $coupon['reduce'];
                }
            }
        }
        return $total_money;
    }

    /**
     * 修改发货状态
     * @param $param
     * @return bool
     * @throws
     */
    public static function editCourier($param)
    {
        $pay = self::where('order_id', $param['order_id'])->value('payment_state');
        if ($pay != 1) {
            return app('json')->fail('未支付的订单无法发货');
        }
        Db::startTrans();
        try {
            $courier['courier'] = $param['courier'];
            $courier['courier_num'] = $param['courier_num'];
            $courier['shipment_state'] = 1;
            self::where('order_id', $param['order_id'])->Update($courier);
            $save['order_id'] = $param['order_id'];
            $save['type_name'] = '录入快递单号';
            $save['content'] = $param['courier'] . '，' . $param['courier_num'];
            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => '快递信息录入失败']);
        }
    }

    /**
     * 添加订单备注信息
     * @param $param
     * @return mixed
     * @throws
     */
    public static function up_remark_model($param)
    {
        Db::startTrans();
        try {
            self::where('order_id', $param['order_id'])->update(['remark_one' => $param['remark']]);
            $save['order_id'] = $param['order_id'];
            $save['type_name'] = '添加备注';
            $save['content'] = $param['remark'];
            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
//            return true;
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => '备注信息录入失败']);
        }
    }

    /**
     * 修改订单价格
     * @param $param
     * @return mixed
     * @throws
     */
    public static function edit_price_model($param)
    {
        Db::startTrans();
        try {
            $order = self::where('order_id', $param['order_id'])->find();
            $order['edit_money'] = $param['price'];
            $order['order_money'] = $order['order_money'] + $order['edit_money'];
            $order['order_num'] = $order['order_num'].'B';
            if ($order['order_money'] <= 0) {
                return app('json')->fail('价格错误');
            }
            $order->save();
            $save['order_id'] = $param['order_id'];
            $save['type_name'] = '修改订单金额';
            $save['content'] = $param['price'];
            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }

    /**
     * 微信退款
     * @param $id
     * @return mixed
     */
    public static function TuiMoney($id)
    {
        $res = Tui::where('id',$id)->where('status',0)->find();
        if (!$res) {
            return app('json')->fail('退款订单异常');
        }
        $tui = new TuiService($id);
        return $tui->pay();
    }

    /**
     * 退款驳回
     * @param $id
     * @param $msg
     * @return mixed
     * @throws
     */
    public static function TuiBoHui($id, $msg)
    {
        //这里的id是tui的id
        $tui = Tui::find($id);
        if (!$tui) {
            return app('json')->fail('id错误');
        }
        $order_id = $tui['order_id'];
        $aid = TokenService::getCurrentAid();
        $data['aid'] = $aid;
        $data['remark'] = $msg;
        $data['status'] = -1;
        Db::startTrans();
        try {
            $tui->save($data);
            $res = self::where(['order_id' => $order_id, 'state' => -1])->update(['state' => 0]);
            Db::commit();
            if (!$res) {
                return app('json')->fail();
            }
            return app('json')->success();
//            return $res ? 1 : 0;
        } catch (Exception $e) {
            Db::rollback();
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }

    /**
     * 关闭订单
     */
    public static function closeOrder()
    {
        $where['state'] = 0;
        $where['payment_state'] = 0;
        $time = time() - 15 * 60; //1关闭15分钟未支付的订单
        self::where($where)->whereTime('create_time', '<', $time)->update(['state' => -3]);
    }

    /**
     * 获取订单指定字段
     * @param $id
     * @param $field
     * @return mixed
     */
    public static function getOrderAttr($id, $field)
    {
        $value = self::where('order_id', $id)->value($field);
        if (!$value) {
            throw new BaseException(['获取字段失败']);
        }
        return $value;
    }


    //关联规格模型
    public function sku()
    {
        return $this->hasMany('GoodsSku', 'goods_id', 'goods_id');
    }

    public function users()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }

    //关联订单商品模型
    public function ordergoods()
    {
        return $this->hasMany('OrderGoods', 'order_id', 'order_id');
    }

    //关联模型
    public function rate()
    {
        return $this->hasMany('Rate', 'order_id', 'order_id');
    }

    //关联图片
    public function imgs()
    {
        return $this->belongsTo('Image', 'goods_picture', 'id')->field('id,url');
    }

    public function setImgsAttr($v)
    {
        return $v['url'];
    }


}