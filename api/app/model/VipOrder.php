<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/9 0009
 * Time: 13:40
 */

namespace app\model;


use bases\BaseCommon;
use bases\BaseModel;
use think\facade\Log;

class VipOrder extends BaseModel
{
    /**
     * 购买Vip
     * @param $uid
     * @param $id
     * @return mixed
     */
    public static function addVip($uid, $id)
    {
        $state = SysConfig::where(['key' => 'is_vip'])->value('value');
        if ($state != 1) {
            return app('json')->fail('未开启会员');
        }
        $tc = VipTc::where('id', $id)->find();
        if (!$tc) {
            return app('json')->fail('套餐不存在');
        }
        $data['order_num'] = (new BaseCommon())->makeOrderNum();
        $data['user_id'] = $uid;
        $data['pay_money'] = $tc['price'];
        $data['order_statue'] = 1;
        $data['day_num'] = $tc['day_num'];
        $res = self::create($data);
        return app('json')->success($res['id']);
    }

    /**
     * 购买成功支付回调
     * @param $id
     * @return mixed
     */
    public static function notifyVip($id)
    {
        try {
            $order = self::where('id', $id)->find();
            $order['order_statue'] = 2;
            $order['pay_time'] = time();
            $order->save();
            $userVip = VipUser::where('user_id', $order['user_id'])->find();
            if ($userVip && $userVip['end_time'] > time()) {
                $time = $userVip['end_time'] + $order['day_num'] * 24 * 60 * 60;;
            } else {
                $time = time() + $order['day_num'] * 24 * 60 * 60;;
            }
            if ($userVip) {
                $userVip->save(['status' => 1, 'end_time' => $time]);
            } else {
                VipUser::create(['user_id' => $order['user_id'], 'status' => 1, 'end_time' => $time]);
            }
            return app('json')->success();
        } catch (\Exception $e) {
            Log::error('购买会员错误:' . $e->getMessage());
            return app('json')->fail();
        }
    }
}