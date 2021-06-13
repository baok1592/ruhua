<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/25 0025
 * Time: 15:10
 */

namespace app\services;


use app\model\Delivery as DeliveryModel;
use app\model\Goods as GoodsModel;
use app\model\Order;
use app\model\OrderGoods;
use app\model\PtItem;
use app\model\User;
use app\model\UserAddress;
use app\Request;
use bases\BaseCommon;
use exceptions\OrderException;
use GzhPay\JsApi;
use think\facade\Db;

class PtOrderService
{
    /**
     * 创建公众号订单
     * @param $post
     * @param $uid
     * @param $type
     * @param $openid
     * @return \GzhPay\json数据，可直接填入js函数作为参数|string
     * @throws OrderException
     */
    public function createGzhOrder($post, $uid, $openid, $type)
    {
        Db::startTrans();
        try {
            if ($type == 1) {
                $order_data = $this->CreatePtItemOrder($post, $uid); //创建订单
            } else if ($type == 2) {
                $order_data = $this->CreatePtOrder($post, $uid); //创建订单
            }
            if (!is_array($order_data)) {
                return $order_data;
            }
            $gzh['web_name'] = app('system')->getValue('web_name');
            $gzh['api_url'] = app('system')->getValue('api_url');
            $res = (new JsApi())->gzh_pay($openid, $order_data['order_data'], $gzh);
            Db::commit();
            return app('json')->success($res);
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }

    /**
     * 创建公众号订单
     * @param $post
     * @param $uid
     * @param $type
     * @return \GzhPay\json数据，可直接填入js函数作为参数|string
     * @throws OrderException
     */
    public function createWxOrder($post, $uid, $type)
    {
        if ($type == 1) {
            $order_data = $this->CreatePtItemOrder($post, $uid); //创建订单
        } else if ($type == 2) {
            $order_data = $this->CreatePtOrder($post, $uid); //创建订单
        }
        if (!is_array($order_data)) {
            return $order_data;
        }
        return app('json')->success($order_data['id']);
    }

    /**
     * 队长创建订单
     * @param $post
     * @param $uid
     * @return array|string
     * @throws OrderException
     */
    public function CreatePtItemOrder($post, $uid)
    {
        Db::startTrans();
        try {
            $goods_info = GoodsModel::with(['sku', 'ptGoods.pt'])->where('goods_id', $post['goods_id'])->find();
            if (!$goods_info['pt_goods'] || !$goods_info['pt_goods']['pt']) {
                return app('json')->fail('商品未开启拼团');
            }
            if (time() < strtotime($goods_info['pt_goods']['pt']['start_time']) || time() > strtotime($goods_info['pt_goods']['pt']['end_time'])) {
                return app('json')->fail('未在活动时间');
            }
            $item_data = $this->setItemData($goods_info, $uid);
            $item = PtItem::create($item_data);
            $order_data = $this->setOrderData($post, $uid, $item, $goods_info);//组装订单数据
            $res_order = Order::create($order_data);//创建订单
            $goods_data = $this->setOrderGoods($res_order['id'], $post, $goods_info, $uid);//组装订单商品数据
            $res_goods = OrderGoods::create($goods_data);//创建订单商品
            $data['id'] = $res_order['id'];
            $data['order_data'] = $order_data;
            Db::commit();
            return $data;
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            return app('json')->fail($e->getMessage());
        }
    }

    /**
     * 团员创建订单
     * @param $post
     * @param $uid
     * @return array|string
     * @throws OrderException
     */
    public function CreatePtOrder($post, $uid)
    {
        Db::startTrans();
        try {
            $goods_info = GoodsModel::with(['sku', 'ptGoods.pt'])->where('goods_id', $post['goods_id'])->find();
            $item = PtItem::where('id', $post['item_id'])->where('state', 1)->find();
            if (!$item) {
                return app('json')->fail('团购未开启或已结束');
            }
            if (!$goods_info['pt_goods'] || !$goods_info['pt_goods']['pt']) {
                return app('json')->fail('商品未开启拼团');
            }
            if (time() > $item['item_time'] || time() > strtotime($goods_info['pt_goods']['pt']['end_time'])) {
                return app('json')->fail('未在活动时间');
            }
            $item_user = Order::where('item_id', $post['item_id'])->where('state', '>=', 0)->where('payment_state', 1)->column('user_id');
            if (in_array($uid, $item_user)) {
                return app('json')->fail('请勿重复参团');
            }
            if (count($item_user) >= $item['user_num']) {
                return app('json')->fail('人数已满');
            }

            if ($goods_info['pt_goods']['pt']['is_new_user'] == 1) {
                $order = Order::where('user_id', $uid)->find();
                if ($order) {
                    return app('json')->fail('非新用户');
                }
            }
            $order_data = $this->setOrderData($post, $uid, $item, $goods_info);//组装订单数据
            $res_order = Order::create($order_data);//创建订单
            $goods_data = $this->setOrderGoods($res_order['id'], $post, $goods_info, $uid);//组装订单商品数据
            $res_goods = OrderGoods::create($goods_data);//创建订单商品
            $data['id'] = $res_order['id'];
            $data['order_data'] = $order_data;

            Db::commit();
            return $data;
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            return app('json')->fail($e->getMessage());
        }
    }

    public function setItemData($goods, $uid)
    {
        $data['user_id'] = $uid;
        $data['goods_id'] = $goods['goods_id'];
        $data['goods_name'] = $goods['goods_name'];
        $data['state'] = 0;
        $data['user_num'] = $goods['pt_goods']['pt']['user_num'];
        $data['img_id'] = $goods['img_id'];
        $data['item_time'] = strtotime("+" . $goods['pt_goods']['pt']['pt_time'] . "hours", time());
        $data['cheng_tuan_num'] = $goods['pt_goods']['pt']['is_cheng_tuan'] ? $goods['pt_goods']['pt']['cheng_tuan_num'] : 0;
        $data['is_cou_tuan'] = $goods['pt_goods']['pt']['is_cou_tuan'];
        return $data;
    }

    /**
     * 组装订单数据
     * @param $post
     * @param $uid
     * @param $item
     * @param $goods
     * @return array
     * @throws OrderException
     */
    public function setOrderData($post, $uid, $item, $goods)
    {
        $data = [];
        $data['order_num'] = (new BaseCommon())->makePtOrderNum();  //订单号
        $data['item_id'] = $item['id'];  //订单号
        $data['user_id'] = $uid;
        $data['user_ip'] = (new Request())->ip(); //买家IP
        $data['state'] = 0;
        $data['pt_state'] = 1;
        $data['payment_state'] = 0;
        $data['shipping_state'] = 0;
        $data['order_from'] = $post['order_from'];  //订单来源 0:小程序,1:wap
        $data['payment_type'] = $post['payment_type']; //支付方式
        $data['message'] = $post['msg'] ? $post['msg'] : ''; //留言
        $data['type'] = $goods['style'];
        $data['activity_type'] = '拼团活动';

        if ($item['user_id'] == $uid) {
            $data['is_captain'] = 1;
        }
        if ($data['type']) {
            $data['yz_code'] = rand(100000, 999999);
        }
        if ($post['sku_id']) {
            foreach ($goods['sku'][0]['json']['list'] as $key => $value) {
                if ($value['id'] == $post['sku_id']) {
                    $data['price'] = $value['price'] - $goods['pt_goods']['price'];
                }
            }
        } else {
            $data['price'] = $goods['price'] - $goods['pt_goods']['price'];
        }
        if ($goods['pt_goods']['pt']['is_buy_num'] == 1 && $post['num'] > $goods['pt_goods']['pt']['buy_num']) {
            throw new OrderException(['msg' => '数量错误']);
        } else {
            $data['num'] = $post['num'];
        }
        if ($post['is_captain_sign'] == 1 && $goods['pt_goods']['pt']['is_sign_one'] == 1) {
            $data['is_captain_sign'] = 1;
            $address = UserAddress::getUserInfo($item['user_id']);//用队长的默认地址
        } else if ($goods['pt_goods']['pt']['is_sign_one'] == 2) {
            $address = UserAddress::getUserInfo($item['user_id']);//用队长的默认地址
        } else {
            $address = UserAddress::getUserInfo($uid);//用自己的默认地址
        }
        $data['shipping_money'] = DeliveryModel::computeShipping($address['region_id'], [$post]); //订单运费
        $data['goods_money'] = $data['price'] * $data['num'];
        $data['order_money'] = $data['shipping_money'] + $data['goods_money'];
        $data['order_money'] = round($data['order_money'], 2);
        if (round($data['order_money'], 2) != round($post['total_price'], 2) || $data['order_money'] < 0.01) {//价格验证
            throw new OrderException(['msg' => '价格错误']);
        }
        $arr = array_merge($data, $address);
        return $arr;
    }

    /**
     * 组装普通商品订单的 商品数据
     * @param $order_id
     * @param $post
     * @param $goods
     * @param $uid
     * @return array
     */
    public function setOrderGoods($order_id, $post, $goods, $uid)
    {
        $data['order_id'] = $order_id;
        $data['goods_id'] = $goods['goods_id'];
        $data['goods_name'] = $goods['goods_name'];
        $data['sku_id'] = $post['sku_id'] ? $post['sku_id'] : 0;
        if ($post['sku_id']) {
            foreach ($goods['sku'][0]['json']['list'] as $key => $value) {
                if ($value['id'] == $post['sku_id']) {
                    $data['price'] = $value['price'] - $goods['pt_goods']['price'];
                    $data['sku_name'] = (array_key_exists('s1_name', $value) ? $value['s1_name'] : '')
                        . ' ' . (array_key_exists('s2_name', $value) ? $value['s2_name'] : '')
                        . ' ' . (array_key_exists('s3_name', $value) ? $value['s3_name'] : '');
                }
            }
        } else {
            $data['price'] = $goods['price'] - $goods['pt_goods']['price'];
        }
        $data['num'] = $post['num'];
        $data['cost_price'] = $goods['cost_price'];
        $data['total_price'] = $data['price'] * $data['num'];
        $data['pic_id'] = $goods['img_id'];  //商品图片ID
        $data['user_id'] = $uid;  //商品图片ID
        return $data;
    }
}