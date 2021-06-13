<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18 0018
 * Time: 11:46
 */

namespace app\model;


use bases\BaseModel;
use exceptions\BaseException;

class Coupon extends BaseModel
{
    protected $type = [
        'start_time' => 'timestamp:Y-m-d',
        'end_time' => 'timestamp:Y-m-d',
    ];

    /**
     *
     * @param $post
     * @return mixed
     */
    public static function addCoupon($post)
    {
        $post['type'] = 1;
        $post['shop_id'] = 1;
        $post['region_id'] = 0;
        if ($post['goods_ids'][0] != 0) {
            foreach ($post['goods_ids'] as $k => $v) {
                $res = Goods::where('goods_id', $v)->find();
                if (!$res) {
                    return app('json')->fail('商品ID错误');
                }
            }
        }
        if ($post['status'] != 1 && $post['status'] != 2) {
            return app('json')->fail('优惠券status错误');
        }
        if (array_key_exists('goods_ids', $post)) {
            $post['goods_ids'] = implode(',', $post['goods_ids']);
        }
        $data = self::setCouponDate($post);
        $res = self::create($data);
        return app('json')->success($res['id']);
//        return $res->id;
    }

    //组装数据
    private static function setCouponDate($post)
    {
        $data['type'] = $post['type'];
        $data['shop_id'] = $post['shop_id'];
        if (array_key_exists('status', $post)) {
            $data['status'] = $post['status'];
        }
        if ($post['stock_type']) {
            $data['stock_type'] = 1;
        } else {
            $data['stock_type'] = 0;
            if (!array_key_exists('stock', $post)) {
                throw new BaseException(['msg' => 'stock未填']);
            }
            $data['stock'] = $post['stock'];
        }
        $data['goods_ids'] = $post['goods_ids'];
        $data['region_id'] = $post['region_id'];
        $data['full'] = $post['full'];
        $data['reduce'] = $post['reduce'];
        $data['name'] = $post['name'];
        if ($post['start_time']) {
            $data['start_time'] = $post['start_time'];
            if (!$post['end_time']) {
                throw new BaseException(['msg' => 'end_time未填']);
            }
            $data['end_time'] = $post['end_time'];
        }
        if ($post['day'] && !$post['start_time']) {
            $data['day'] = $post['day'];
        }
        if(!$post['start_time']&& !$post['day']){
            throw new BaseException(['msg' => '请设置用券时间']);
        }
        return $data;
    }
}