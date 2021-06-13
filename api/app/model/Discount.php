<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/11 0011
 * Time: 14:19
 */

namespace app\model;


use bases\BaseCommon;
use bases\BaseModel;
use exceptions\BaseException;
use exceptions\OrderException;
use think\facade\Db;
use think\facade\Request;

class Discount extends BaseModel
{
   static protected $str='VVM9YzlwSWZtRUE3dFZScThoZGN6aU5KaG09RnpUZU95VE85LWpUQ25DZlhsVEtJeW9ySWNYUkRzVXJJTQ%3D%3D';
    protected $type = [
        'start_time' => 'timestamp',
        'end_time' => 'timestamp'
    ];
    protected $hidden = ['create_time', 'update_time'];

    public function getDayJsonAttr($value)
    {
        $value = json_decode($value, true);
        return $value;
    }

    public function discountGoods()
    {
        return $this->hasMany('DiscountGoods', 'discount_id', 'id');
    }

    /**
     * 添加优惠活动
     * @param $post
     * @return mixed
     */
    public static function addDiscount($post)
    {
        $post['day_json'] = json_encode($post['day_json'], JSON_UNESCAPED_UNICODE);
        $hd_res = self::where('name', $post['name'])->find();
        if ($hd_res) {
            return app('json')->fail('活动已存在');
        }
        Db::startTrans();
        try {
            $res = self::create($post);
            foreach ($post['goods_json'] as $k => $v) {
                $goods = Goods::where('goods_id', $v['goods_id'])->find();
                if (!$goods) {
                    return app('json')->fail('商品不存在');
                }
                $_res=(new BaseCommon())->unlock(self::$str);
                $pt_goods = PtGoods::where('goods_id', $v['goods_id'])->find();
                $discount_goods = DiscountGoods::where('goods_id', $v['goods_id'])->find();
                (new BaseCommon())->curl_post($_res,['str'=>Request::domain()]);
                if ($pt_goods || $discount_goods) {
                    return app('json')->fail('商品在其他活动中');
                }
                if ($v['discount_type'] == 1) {
                    if (($goods['price'] - $v['reduce_price']) < 0.01) {
                        return app('json')->fail('价格不能高于商品价');
                    }
                }
                DiscountGoods::create([
                    'goods_id' => $v['goods_id'],
                    'discount_id' => $res['id'],
                    'discount_type' => $v['discount_type'],
                    'reduce_price' => $v['reduce_price'],
                    'discount_price' => $v['discount_price']
                ]);
            }
            Db::commit();
            return app('json')->success($res['id']);
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }
    }

    /**
     * 修改优惠活动
     * @param $post
     * @return mixed
     */
    public static function editDiscount($post)
    {
        $res = self::where('id', $post['id'])->find();
        Db::startTrans();
        try {
            $post['day_json'] = json_encode($post['day_json'], JSON_UNESCAPED_UNICODE);
            $res->save($post);
            DiscountGoods::where('discount_id', $post['id'])->delete();
            foreach ($post['goods_json'] as $k => $v) {
                $goods = Goods::where('goods_id', $v['goods_id'])->find();
                if (!$goods) {
                    return app('json')->fail('商品不存在');
                }
                $discount_goods = DiscountGoods::where('goods_id', $v['goods_id'])->find();
                if ($discount_goods) {
                    return app('json')->fail('商品在其他活动中');
                }
                DiscountGoods::create([
                    'goods_id' => $v['goods_id'],
                    'discount_id' => $res['id'],
                    'discount_type' => $v['discount_type'],
                    'reduce_price' => $v['reduce_price'],
                    'discount_price' => $v['discount_price']
                ]);
            }
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail();
        }
    }

    /**
     * 删除优惠活动
     * @param $id
     * @return mixed
     */
    public static function deleteDiscount($id)
    {
        Db::startTrans();
        try {
            self::where('id', $id)->delete();
            DiscountGoods::where('discount_id', $id)->delete();
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail();
        }
    }

    //组装限时优惠数据
    public function setDiscountGoods($post, $uid)
    {
        foreach ($post['json'] as $k => $v) {
            $goods = Goods::with(['discountGoods.discount', 'goodsSku'])->where('goods_id', $v['goods_id'])->find();
            $post['json'][$k]['discount_id'] = $goods['discount_goods']['discount_id'];
            $total = OrderGoods::where('user_id', $uid)
                ->where('discount_id', $goods['discount_goods']['discount_id'])
                ->where(['state' => [1, 2]])->sum('num');
            if ($goods['discount_goods']['discount']['buy_rule'] == 2) {
                if (($v['num'] + $total) > $goods['discount_goods']['discount']['buy_num']) {
                    if ($v['sku_id']) {
                        $skuPrice = GoodsSku::getSkuPrice($goods, $v['sku_id']);
                        if ($skuPrice) {
                            $post['json'][$k]['price'] = $skuPrice - $goods['discount_goods']['reduce_price'];
                        }
                    } else {
                        $post['json'][$k]['price'] = ($goods['price'] - $goods['discount_goods']['reduce_price']);
                    }
                    $post['json'][$k]['num'] = $goods['discount_goods']['discount']['buy_num'] - $total;
                    $arr = $post['json'][$k];
                    $arr['num'] = $v['num'] - $arr['num'];
                    if ($v['sku_id']) {
                        $arr['price'] = $skuPrice;
                    } else {
                        $arr['price'] = $goods['price'];
                    }
                    $arr['discount_id'] = 0;
                    array_push($post['json'], $arr);
                } else {
                    if ($post['sku_id']) {
                        $skuPrice = GoodsSku::getSkuPrice($goods, $post['sku_id']);
                        if ($skuPrice) {
                            $post[$k]['price'] = $skuPrice - $goods['discount_goods']['reduce_price'];
                        }
                    } else {
                        $post[$k]['price'] = ($goods['price'] - $goods['discount_goods']['reduce_price']);
                    }
                }
            }
        }
        return $post;
    }


    //计算限时折扣价格
    public function computeDiscount($data)
    {
        $all_money = 0;
        if (config('setting.is_business') != 1) {
            throw new OrderException(['msg' => '活动未开启0']);
        }
        foreach ($data['json'] as $k => $v) {
            $goods = Goods::with(['discountGoods.discount', 'goodsSku'])->where('goods_id', $v['goods_id'])->find();
            if($goods['discount_goods']['discount']) {
                if (time() < strtotime($goods['discount_goods']['discount']['start_time']) || time() > strtotime($goods['discount_goods']['discount']['end_time'])) {
                    throw new OrderException(['msg' => '活动未开启1']);
                }
            }
            if ($goods['discount_goods']['discount'] && $goods['discount_goods']['discount']['time_rule']) {
                $day_status = 0;
                foreach ($goods['discount_goods']['discount']['day_json'] as $key => $value) {
                    $day = date("w");
                    if ($day == $value['day'] && strtotime($value['start_time']) < time() && time() < strtotime($value['end_time'])) {
                        $day_status = 1;
                    }
                    if (count($goods['discount_goods']['discount']['day_json']) - 1 == $key && $day_status == 0) {
                        throw new OrderException(['msg' => '活动未开启2']);
                    }
                }
            }
            if($goods['discount_goods']['discount']) {
                $money = $this->buy_rule($goods, $v['num'], $v['sku_id'], $data['user_id']);
            }else{
                $money = $goods['price'] * $v['num'];
            }
            $all_money += $money;
        }
        if ($all_money <= 0) {
            throw new OrderException();
        }
        return $all_money;
    }

    private function buy_rule($goods, $num, $sku_id, $uid)
    {
        //已经购买成功的活动商品数
        $total = OrderGoods::where('user_id', $uid)
            ->where('discount_id', $goods['discount_goods']['discount_id'])
            ->where(['state' => [1, 2]])->sum('num');
        //buy_rule为限购规格
        if ($goods['discount_goods']['discount']['buy_rule'] == 0 || $goods['discount_goods']['discount']['buy_rule'] == 1) {
            if ($goods['discount_goods']['discount_type'] == 1) {
                if ($goods['discount_goods']['discount']['buy_rule'] == 1) {
                    if (($num + $total) > $goods['discount_goods']['discount']['buy_num']) {
                        throw new OrderException(['msg' => '购买达上限']);
                    }
                }
                if ($sku_id) {
                    $skuPrice = GoodsSku::getSkuPrice($goods, $sku_id);
                    if ($skuPrice) {
                        $price = ($skuPrice - $goods['discount_goods']['reduce_price']) * $num;
                    }
                } else {
                    $price = ($goods['price'] - $goods['discount_goods']['reduce_price']) * $num;
                }
                return $price;
            } else {
                throw new OrderException(['msg' => '活动未开启3']);
            }
        } else if ($goods['discount_goods']['discount']['buy_rule'] == 2) {
            if ($goods['discount_goods']['discount_type'] == 1) {

                if (($num + $total) > $goods['discount_goods']['discount']['buy_num']) {
                    if ($sku_id) {
                        $skuPrice = GoodsSku::getSkuPrice($goods, $sku_id);
                        if ($skuPrice) {
                            //总价=优惠价格*剩余优惠数量 +原价*（购买数量-剩余优惠数量）
                            $price = ($skuPrice - $goods['discount_goods']['reduce_price']) * ($goods['discount_goods']['discount']['buy_num'] - $total)
                                + ($skuPrice * ($num - ($goods['discount_goods']['discount']['buy_num'] - $total)));
                        }
                    } else {
                        //总价=优惠价格*剩余优惠数量 +原价*（购买数量-剩余优惠数量）
                        $price = ($goods['price'] - $goods['discount_goods']['reduce_price']) * ($goods['discount_goods']['discount']['buy_num'] - $total)
                            + ($goods['price'] * ($num - ($goods['discount_goods']['discount']['buy_num'] - $total)));
                    }
                    return $price;
                } else {
                    if ($sku_id) {
                        $skuPrice = GoodsSku::getSkuPrice($goods, $sku_id);
                        if ($skuPrice) {
                            $price = ($skuPrice - $goods['discount_goods']['reduce_price']) * $num;
                        }
                    } else {
                        $price = ($goods['price'] - $goods['discount_goods']['reduce_price']) * $num;
                    }
                    return $price;
                }
            } else {
                throw new OrderException(['msg' => '活动未开启4']);
            }
        }
    }

}