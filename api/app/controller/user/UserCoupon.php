<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18 0018
 * Time: 12:57
 */

namespace app\controller\user;


use app\services\TokenService;
use bases\BaseController;
use services\QyFactory;
use app\model\UserCoupon as UserCouponModel;

class UserCoupon extends BaseController
{
    /**
     * 用户查看优惠券
     * @param string $type
     * @param int $shop
     * @return mixed
     */
    public function getCoupon()
    {
        $coupon = (new QyFactory())->instance('UserService');
        $data = $coupon->get_coupon_list();
        return app('json')->success($data);
//        return json($data);
    }

    /**
     * 用户领取优惠券
     * @param $id
     * @return mixed
     */
    public function addUserCoupon($id)
    {
        return UserCouponModel::addUserCoupon($id);
    }

    /**
     * 用户查看自己的优惠券
     * @return \think\Collection
     */
    public function selectUserCoupon()
    {
        $uid = TokenService::getCurrentUid();
        $this->validateCoupon($uid); //验证过期
        $res = UserCouponModel::where('user_id', $uid)->select();
        return app('json')->success($res);
        //        return $res;
    }

    /**
     * 验证是否过期
     * @param $uid
     * @return static
     */
    private function validateCoupon($uid)
    {
        $res = UserCouponModel::where('user_id', $uid)->whereTime('end_time', '<', time())->update(['status' => 3]);
        return $res;
    }

    /**
     * 用户查看订单可用优惠券
     * @return mixed
     */
    public function selectStatusCoupon()
    {
        $uid = TokenService::getCurrentUid();
        $post = input('post.');
        $this->validate($post,['json'=>'require']);
        $this->validateCoupon($uid);
        return UserCouponModel::couponStatus($uid,$post['json']);
    }

}