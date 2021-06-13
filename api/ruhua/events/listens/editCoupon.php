<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31 0031
 * Time: 8:34
 */

namespace events\listens;


use app\model\UserCoupon as UserCouponModel;
use exceptions\OrderException;

class editCoupon
{
    public function handle($event)
    {
        $post=$event;
        $this->editCoupon($post);
    }

    /**
     * 优惠券使用完成
     * @param $data
     * @throws OrderException
     */
    public function editCoupon($data){
        if($data['coupon_id']){

            $coupon=UserCouponModel::where('id',$data['coupon_id'])->update(['status'=>2]);
            if(!$coupon){
                throw new OrderException();
            }
        }
    }
}