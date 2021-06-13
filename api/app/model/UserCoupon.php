<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18 0018
 * Time: 13:23
 */

namespace app\model;


use app\services\TokenService;
use app\model\Coupon as CouponModel;
use app\model\UserCoupon as UserCouponModel;
use bases\BaseModel;
use exceptions\BaseException;
use think\facade\Db;

class UserCoupon extends BaseModel
{

    protected $type = [
        'end_time'  =>  'timestamp:Y-m-d',
    ];

    /**
     * 用户领取优惠券
     * @param $id
     * @return mixed
     * @throws BaseException
     */
    public static function addUserCoupon($id)
    {
        $uid = TokenService::getCurrentUid();
        $coupon = CouponModel::where('id', $id)->find();
        $userCoupon = UserCouponModel::where('user_id', $uid)->where('coupon_id', $id)->order('id', 'desc')->find();
        if (!$coupon) {
            return app('json')->fail('优惠券错误');
        }
        if ($coupon['stock_type'] == 0 && $coupon['stock'] == 0) {
            return app('json')->fail('库存不够');
        } else if ($coupon['stock_type'] == 0) {
            $coupon['stock'] -= 1;
        }
        if ($coupon['status'] == 1 && $userCoupon) {
           return app('json')->fail('该优惠券只能领取一次');
        } else if ($coupon['status'] == 2 && $userCoupon) {
            if ($userCoupon['status'] != 2 && $userCoupon['status'] != 3) {
                return  app('json')->fail('已领取');
            }
        }
        $data['user_id'] = $uid;
        $data['shop_id'] = $coupon['shop_id'];
        $data['coupon_id'] = $id;
        $data['goods_ids'] = $coupon['goods_ids'];
        $data['full'] = $coupon['full'];
        $data['reduce'] = $coupon['reduce'];
        if ($coupon['end_time']) {
            $data['end_time'] = strtotime($coupon['end_time']);
        } else {
            $data['end_time'] = strtotime('+' . $coupon['day'] . ' day', time());
        }
        Db::startTrans();
        try {
            $res = UserCouponModel::create($data);
            $coupon->save();
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new BaseException(['msg' => $e->getMessage()]);
        }
        return app('json')->success($res['id']);
//        return $res['id'];
    }

    /**
     * 用户查看订单可用优惠券
     * @param $uid
     * @param $ids
     * @return mixed
     */
    public static function  couponStatus($uid, $data)
    {
        $coupon = UserCouponModel::where('user_id', $uid)->where('status', 0)
            ->whereTime('end_time', '>', time())
            ->select();
        foreach ($coupon as $k => $v) {
            $total_money=0;
            foreach ($data as $key => $value) {
                if ($v['goods_ids'] != 0) {
                    $coupon_ids = explode(',', $v['goods_ids']);
                    if (in_array($value['goods_id'], $coupon_ids)) {
                        $money=$value['price']*$value['num'];
                        $total_money+=$money;
                    }

                }else{
                    $money=$value['price']*$value['num'];
                    $total_money+=$money;

                }
            }
            if($total_money<$v['full']){
                unset($coupon[$k]);
            }
        }
        return app('json')->success($coupon);
//        return $coupon;
    }

    /**
     * 删除用户过期优惠券
     */
    public static function delUserCoupon(){
        $time=time()-10*24*60*60;
        self::where('status',3)->whereTime('end_time','<',$time)->delete();
    }
}