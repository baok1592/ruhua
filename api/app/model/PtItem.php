<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/25 0025
 * Time: 16:08
 */

namespace app\model;


use app\services\TuiService;
use bases\BaseModel;
use exceptions\OrderException;
use think\facade\Log;

class PtItem extends BaseModel
{
    //关联模型
    public function user()
    {
        return $this->hasOne('User', 'id', 'user_id')->field('id,nickname,headpic');
    }

    /**
     * 获取拼团队伍
     * @param $goods_id
     * @return mixed
     */
    public static function getItem($goods_id)
    {
        $res = self::where('goods_id', $goods_id)->where('state', 1)
            ->whereTime('item_time', '>', time())
            ->field('id,user_num - pay_user as num,pay_user,item_time,user_id,is_cou_tuan')
            ->order('num')->limit(6)->select()->toArray();

        foreach ($res as $k => $v) {
            $res[$k]['item_pic'] = [];
            $res[$k]['c_pic'] = [];
            $order = Order::with(['users'])->where(['item_id' => $v['id'], 'payment_state' => 1])->field('user_id')->select();
            foreach ($order as $kk => $vv) {
                if ($v['user_id'] == $vv['user_id']) {
                    $res[$k]['c_pic'] = $vv['users']['headpic'];
                    $res[$k]['c_name'] = $vv['users']['nickname'];
                } else {
                    array_push($res[$k]['item_pic'], $vv['users']['headpic']);
                }
            }
        }
        return app('json')->success($res);
    }

    /**
     * 获取某个拼团详情
     * @param $id
     * @return mixed
     */
    public static function getOneItem($id)
    {
        $res = self::where('id', $id)->where('state', 1)
            ->whereTime('item_time', '>', time())
            ->field('id,user_num - pay_user as num,pay_user,item_time,user_id,is_cou_tuan')
            ->order('num')->find();
        if(!$res){
            return app('json')->success();
        }
        $res=$res->toArray();
        $res['item_pic'] = [];
        $res['c_pic'] = [];
        $order = Order::with(['users'])->where(['item_id' => $res['id'], 'payment_state' => 1])->field('user_id')->select();
        foreach ($order as $kk => $vv) {
            if ($res['user_id'] == $vv['user_id']) {
                $res['c_pic'] = $vv['users']['headpic'];
                $res['c_name'] = $vv['users']['nickname'];
            } else {
                array_push($res['item_pic'], $vv['users']['headpic']);
            }
        }
        return app('json')->success($res);
    }

    /**
     * 检测拼团队伍是否完成
     * @param $id
     * @return int
     * @throws OrderException
     */
    public static function checkItemUser($id)
    {
        $res = Order::where('order_id', $id)->find();
        if ($res['item'] == 0) {
            return 1;
        }
        $item = $res['item'];
        $item = self::with('user')->where('id', $item)->find();
        if ($res['user_id'] == $item['user_id']) {
            $state = 0;
        } else {
            $state = 1;
        }
        if (!$item || $item['state'] != $state || $item['user_num'] <= $item['pay_user'] || $item['item_time'] < time()) {
            throw new OrderException(['msg' => '拼团订单不存在或已完成']);
        }
        return 1;
    }

    /**
     * 关闭拼团订单
     */
    public static function closeItem()
    {
        $itemModel = new self();
        $orderModel = new Order();
        try {
            $itemModel->where(['state' => 0])->whereTime('item_time', '<', time())->delete();
            $item = $itemModel->with(['user'])->where(['state' => 1])->whereTime('item_time', '<', time())->select();
            foreach ($item as $k => $v) {
                if (($v['pay_user'] + $v['cheng_tuan_num']) >$v['user_num']){       //虚拟成团
                    $itemModel->where(['id'=>$v['id']])->save(['state'=>2]);
                    $orderModel->where(['item_id'=>$v['id'],'payment_state'=>1])->update(['pt_state'=>2]);
                    $ids = Order::where('item_id', $item['id'])->where('payment_state', 1)->column('user_id');
                    event('SendGzhDeliveryMessage', [$item, 3, $ids]);//拼团成功发送公众号模板消息
                    break;
                }
                $itemModel->where(['id'=>$v['id']])->save(['state'=>-1]);
                $order = $orderModel->where('item_id', $v['id'])->where('payment_state', 1)->select();
                foreach ($order as $kk => $vv) {
                    $tui = new TuiService();
                    $tui->ptTuiPay($vv['order_id']);
                    $vv['goods_name']=substr($v['goods_name'],0,10);
                    event('SendGzhDeliveryMessage', [$vv, 4, $vv['user_id']]);//拼团失败发送公众号模板消息
                }
            }

        } catch (\Exception $e) {
            Log::error('关闭拼团:' . $e->getMessage());
        }
    }
}