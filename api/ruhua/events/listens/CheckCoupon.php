<?php
namespace events\listens;

use app\model\UserCoupon as UserCouponModel;
use exceptions\OrderException;

class CheckCoupon
{
    public function handle($event)
    {
        $post=$event;
        $this->checkCoupon($post);
        //这里可以接队列
//        Queue::push('job\Job1', $data = '立即执行', $queue = null);
    }

    public function checkCoupon($data){
        if(!$data['coupon_id']){
            return '';
        }
        $coupon=UserCouponModel::where('id',$data['coupon_id'])->where('status',0)->whereTime('end_time','>',time())->find();
        if(!$coupon){
            throw new OrderException(['msg'=>'优惠券使用错误']);
        }
        if($coupon['reduce']!=$data['coupon_price']){
            throw new OrderException(['msg'=>'优惠券使用错误']);
        }

        $total_money=0;
        foreach ($data['json'] as $key => $value) {
            if ($coupon['goods_ids'] != 0) {
                $coupon_ids = explode(',', $coupon['goods_ids']);
                if (in_array($value['goods_id'], $coupon_ids)) {
                    $money=$value['price']*$value['num'];
                    $total_money+=$money;
                    return $total_money;
                }
            }else{
                $money=$value['price']*$value['num'];
                $total_money+=$money;
            }
        }
        if($total_money<$coupon['full']){
            throw new OrderException(['msg'=>'优惠券使用错误']);
        }

    }
}