<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/30 0030
 * Time: 11:03
 */

namespace app\controller\cms;


use app\model\DiscountGoods as DiscountGoodsModel;
use app\validate\DiscountValidate;
use app\model\Discount as DiscountModel;
use app\validate\IDPostiveInt;
use bases\BaseController;

class DiscountManage extends BaseController
{

    /**
     * 添加优惠活动
     * @return mixed
     */
    public function addDiscount()
    {
        (new DiscountValidate())->goCheck();
        $post = input('post.');
        return DiscountModel::addDiscount($post);
    }

    /**
     * 修改优惠活动
     */
    public function editDiscount()
    {
        $post = input('post.');
        return DiscountModel::editDiscount($post);
    }

    /**
     * 删除优惠活动
     * @param $id
     * @return mixed
     */
    public function deleteDiscount($id)
    {
        (new IDPostiveInt)->goCheck();
        return DiscountModel::deleteDiscount($id);
    }

    /**
     * 获取所有优惠活动
     * @return mixed
     */
    public function getDiscount()
    {
        $res = DiscountModel::with('discountGoods.goods')->select();
        foreach ($res as $k=>$v){
            foreach ($v['discount_goods'] as $kk=>$vv){
                if(!$vv['goods']){
                    unset($res[$k]['discount_goods'][$kk]);
                }
            }
        }
        return app('json')->success($res);
    }

    /**
     * 获取限时优惠商品
     * @return mixed
     */
    public function getDiscountGoods()
    {
        $res = DiscountGoodsModeL::getAllDiscountGoods();
        if ($res) {
            return app('json')->success($res);
        }
        return app('json')->fail();
    }

}