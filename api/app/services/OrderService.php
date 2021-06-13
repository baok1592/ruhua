<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18 0018
 * Time: 15:54
 */

namespace app\services;


use app\model\Delivery as DeliveryModel;
use app\model\Discount;
use app\model\User as UserModel;
use app\model\Goods as GoodsModel;
use app\model\GoodsSku;
use app\model\Order as OrderModel;
use app\model\OrderGoods as OrderGoodsModel;
use app\model\SysConfig;
use app\model\UserAddress;
use app\model\UserCoupon as UserCouponModel;
use app\model\VipUser;
use app\Request;
use bases\BaseCommon;
use exceptions\BaseException;
use exceptions\OrderException;
use GzhPay\JsApi;
use think\facade\Db;

class OrderService
{

    /**
     * 创建公众号订单
     * @param $post
     * @return \GzhPay\json数据，可直接填入js函数作为参数|string
     * @throws OrderException
     */
    public function createGzhOrder($post)
    {
        $openid = TokenService::getCurrentTokenvar('openid');
        $uid = TokenService::getCurrentUid();
        Db::startTrans();
        try {
            $order_data = $this->CreateCartOrder($post, $uid); //创建订单
            $gzh['web_name'] = SysConfig::where(['key' => 'web_name'])->value('value');
            $gzh['api_url'] = SysConfig::where(['key' => 'api_url'])->value('value');
            $res = (new JsApi())->gzh_pay($openid, $order_data['order_data'], $gzh);
            $res = json_decode($res, true);
            Db::commit();
            return $res;
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }


    /**
     * 创建订单
     * @param $post
     * @param $uid
     * @return array|string
     * @throws OrderException
     */
    public function CreateCartOrder($post, $uid)
    {
        event("CreateOrder", $post);//监听订单，检测商品是否都是虚拟或都是实物，优惠券，库存
        Db::startTrans();
        try {
            $order_data = $this->setOrderData($post, $uid);//组装订单数据
            $res_order = OrderModel::create($order_data);//创建订单
            $oid = $res_order->id;
            $goods = $this->setOrderGoods($oid, $post, $uid);//组装订单商品数据
            (new OrderGoodsModel)->saveall($goods);

            $data['id'] = $oid;
            $data['order_data'] = $order_data;
            Db::commit();
            return $data;
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }

    /**
     * 组装订单数据
     * @param $post
     *
     * @param $uid
     * @return array
     * @throws OrderException
     */
    public function setOrderData($post, $uid)
    {
        $data = [];
        $data['order_num'] = (new BaseCommon())->makeOrderNum();  //订单号
        $data['order_from'] = $post['order_from'];  //订单来源 0:小程序,1:wap
        $data['payment_type'] = $post['payment_type']; //支付方式
        $data['message'] = $post['msg'] ? $post['msg'] : ''; //留言
        $data['invite_code'] = array_key_exists('sfm',$post) ? $post['sfm'] :''; //身份码
        $data['invoice'] = array_key_exists('invoice',$post) ? $post['invoice'] : 0; //发票类型
        $data['user_id'] = $uid;
        $post['user_id'] = $uid;            //uid 写入post
        $data['user_ip'] = (new Request())->ip(); //买家IP
        foreach ($post['json'] as $k => $v) {
            $data['type'] = GoodsModel::where('goods_id', $v['goods_id'])->value('style');
        }
        //获取信息及用户地址
        $user_data = UserAddress::getUserInfo($uid);//直接用默认地址
        $data['shipping_money'] = DeliveryModel::computeShipping($user_data['region_id'], $post['json']); //订单运费

        $data['goods_money'] = $this->computeGoodsMoney($post);    //商品总价'

        $data['coupon_money'] = $this->computeCouponMoney($post['coupon_id'], $uid, $post['json']);    //优惠券价格
        $data['order_money'] = $data['shipping_money'] + $data['goods_money'] - $data['coupon_money'];
        if ($data['order_money'] != $post['total_price'] || $data['order_money'] <= 0) {//价格验证
            // throw new OrderException(['msg' => '价格错误s'.$data['order_money'].'--'.$post['total_price'].'--'.$data['goods_money']]);
            throw new OrderException(['msg' => '价格错误']);
        }
        if ($data['type']) {
            $data['yz_code'] = rand(100000, 999999);
        }
        if ($post['coupon_id']) {
            $data['coupon_id'] = $post['coupon_id'];
        }
        if ($post['discount'] == 1) {                   //限时优惠
            $data['activity_type'] = '限时优惠';
        }
 
        $arr = array_merge($data, $user_data);
        return $arr;
    }

    /**
     * 组装普通商品订单的 商品数据
     * @param $order_id
     * @param $post
     * @param $uid
     * @return array
     */
    public function setOrderGoods($order_id, $post, $uid)
    {
        $data = [];
        if (config('setting.is_business') == 1 && $post['discount'] == 1) {                //限时折扣
            $post = (new Discount())->setDiscountGoods($post, $uid);//组装限时折扣商品
        }
        foreach ($post['json'] as $k => $v) {
            $pinfo = GoodsModel::getProductByID($v['goods_id']);//获取商品及关联数据
            $data[$k]['goods_id'] = $v['goods_id'];
            $data[$k]['goods_name'] = $pinfo['goods_name'];
            $data[$k]['sku_id'] = $v['sku_id'];
            if ($v['sku_id']) {
                foreach ($pinfo['sku'][0]['json']['list'] as $key => $value) {
                    if ($value['id'] == $v['sku_id']) {
                        $data[$k]['price'] = $value['price'];
                        $data[$k]['sku_name'] = (array_key_exists('s1_name', $value) ? $value['s1_name'] : '')
                            . ' ' . (array_key_exists('s2_name', $value) ? $value['s2_name'] : '')
                            . ' ' . (array_key_exists('s3_name', $value) ? $value['s3_name'] : '');
                    }
                }
            } else {
                $data[$k]['price'] = $pinfo['price'];
            }
            if (config('setting.is_business') == 1 && $post['discount'] == 1) {
                $data[$k]['price'] = $v['price'];
                $data[$k]['discount_id'] = $v['discount_id']?$v['discount_id']:0;
            }
            $data[$k]['num'] = $v['num'];
            $data[$k]['cost_price'] = $pinfo['cost_price'];
            $data[$k]['user_id'] = $uid;
            $data[$k]['order_id'] = $order_id;
            $data[$k]['total_price'] = $data[$k]['price'] * $data[$k]['num'];
//            $data[$k]['pic_id'] = self::getGoodsImg($pinfo, $post);  //商品图片ID
            $data[$k]['pic_id'] = $pinfo['img_id'];  //商品图片ID
        }
        return $data;
    }

    /**
     * 检测商品是否都是虚拟或都是实物
     * @param $ids
     * @return int
     */
    public function check_style($ids)
    {
        $arr = GoodsModel::where('goods_id', 'in', $ids)->column('style');
        $res = 1;
        foreach ($arr as $v) {
            if ($arr[0] != $v) {
                $res = 0;
            }
        }
        return $res;
    }

    /**
     * 判断返回商品图片或规格图片
     * @param $pinfo
     * @param $post
     * @return bool
     */
    public function getGoodsImg($pinfo, $post)
    {
        //无规格的订单商品
        if (empty($post['sku_id'])) {
            return $pinfo['img_id'];
        } else {
            //有规格的订单商品
            foreach ($pinfo['sku'] as $k => $v) {
                if ($v['sku_id'] == $post['sku_id']) {
                    return $v['picture'];
                }
            }
        }
        return true;
    }


    //计算商品总价
    private function computeGoodsMoney($data)
    {
        $all_money = 0;

        if (config('setting.is_business') == 1 && $data['discount'] == 1) {                   //计算限时优惠价格
            return (new Discount())->computeDiscount($data);
        }

        foreach ($data['json'] as $k => $v) {
            $goods = GoodsModel::with(['goodsSku'])->where('goods_id', $v['goods_id'])->find()->toArray();
            if ($v['sku_id']) {
                foreach ($goods['goods_sku']['json']['list'] as $key => $value) {
                    if ($v['sku_id'] == $value['id']) {
                        $money = $v['num'] * $value['price'];
                        $all_money += $money;
                    }
                }
            } else {
                $money = $v['num'] * $goods['price'];
                $all_money += $money;
            }
            if (app('system')->getValue('is_vip') == 1) {                   //如果开启会员购买
                $vip = VipUser::where('user_id', $data['user_id'])->find();
                if ($vip && $vip['end_time'] > time()) {
                    $vip_money = $v['num'] * $goods['vip_price'];
                    $all_money -= $vip_money;
                }
            }
        }

        return round($all_money, 2);
    }

    //计算优惠券
    private function computeCouponMoney($coupon_id, $uid, $data)
    {
        if ($coupon_id) {
            $coupon = UserCouponModel::where('id', $coupon_id)->where('user_id', $uid)->where('status', 0)->whereTime('end_time', '>', time())->find();
            if (!$coupon) {
                throw new OrderException(['msg' => '优惠券使用错误']);
            }
            $all_money = 0;
            foreach ($data as $k => $v) {
                $goods = GoodsModel::with('goodsSku')->where('goods_id', $v['goods_id'])->find()->toArray();
                if ($v['sku_id']) {
                    $skuPrice = GoodsSku::getSkuPrice($goods, $v['sku_id']);//获取规格价格
                    if ($skuPrice) {
                        $money = $this->getCouponMoney($coupon, $v['num'], $skuPrice, $v['goods_id']);
                        $all_money += $money;
                    }
                } else {
                    $money = $this->getCouponMoney($coupon, $v['num'], $goods['price'], $v['goods_id']);
                    $all_money += $money;
                }
            }
            if ($all_money < $coupon['full']) {
                throw new OrderException(['msg' => '优惠券使用错误']);
            }
            $coupon->save(['status' => 1]);
            return $coupon['reduce'];
        }
        return 0;
    }

    //计算价格
    private function getCouponMoney($coupon, $num, $price, $goods_id)
    {
        $total_money = 0;
        if ($coupon['goods_ids'] != 0) {
            $coupon_ids = explode(',', $coupon['goods_ids']);
            if (in_array($goods_id, $coupon_ids)) {
                $money = $price * $num;
                $total_money += $money;
            }
        } else {
            $money = $price * $num;
            $total_money += $money;
        }
        return $total_money;
    }

}