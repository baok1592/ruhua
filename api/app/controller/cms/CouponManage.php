<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18 0018
 * Time: 11:39
 */

namespace app\controller\cms;


use app\model\Coupon as CouponModel;
use bases\BaseController;
use services\QyFactory;

class CouponManage extends BaseController
{
    /**
     * 商家创建店铺优惠券
     * @return mixed
     */
    public function addCoupon()
    {
        $rule = [
            'full' => 'require|float',
            'reduce' => 'require|float',
            'name' => 'require|max:60',
            'status' => 'require',
        ];
        $post = input('post.');
        $this->validate($post, $rule,[],true);
        return CouponModel::addCoupon($post);
    }

    /**
     * cms查看优惠券
     * @return \think\response\Json
     */
    public function getCoupon()
    {
        $coupon=(new QyFactory())->instance('CmsService');
        $data=$coupon->get_coupon_list();
        return app('json')->success($data);
    }

    /**
     * 删除优惠券
     * @param $id
     * @return int
     */
    public function deleteCoupon($id)
    {
        $res = CouponModel::where('id', $id)->delete();
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
//        return $res?1:0;
    }

}