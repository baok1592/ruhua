<?php

namespace app\services;


use app\model\UserCoupon as UserCouponModel;
use app\model\Article as ArticleModel;
use app\model\BannerItem as BannerItemModel;
use app\model\Category as CategoryModel;
use app\model\Coupon as CouponModel;
use app\model\Order as OrderModel;
use app\model\Tui as TuiModel;
use app\model\User as UserModel;
use interfaces\RoleInterface;

class UserService implements RoleInterface
{

    //文章列表
    public function get_article_list()
    {
        // TODO: Implement get_article_list() method.
        $data = ArticleModel::where(['is_hidden' => 1])->select();
        return $data;
    }

    //广告列表
    public function get_banner_list()
    {
        $data = BannerItemModel::with(['imgs', 'banner'])->where('is_visible', 1)->select();
        return $data;
    }

    //分类列表
    public function get_category_list()
    {
        $data = CategoryModel::with('imgs')->where('is_visible', 1)->order('sort asc')->select();
        if (!$data || count($data) < 1) {
            return app('json')->fail('请至少添加一个商品分类');
        }
        return $data;
    }

    //优惠券列表
    public function get_coupon_list()
    {
        $uid = TokenService::getCurrentUid();
        $data['type'] = 1;
        $data['shop_id'] = 1;
        $data['region_id'] = 0;
        $user_coupon = UserCouponModel::where('user_id', $uid)->select();
        $res = CouponModel::where($data)->select();
        foreach ($res as $k => $v) {
            $res[$k]['uesr_status'] = 1;
            if (strtotime($v['end_time']) && strtotime($v['end_time']) < time()) {
                unset($res[$k]);
                continue;
            }
            if ($v['goods_ids'] != 0) {
                $res[$k]['goods_ids'] = explode(',', $v['goods_ids']);
            }
            if ($v['status'] == 1) {
                foreach ($user_coupon as $value) {
                    if ($v['id'] == $value['coupon_id']) {
                        $res[$k]['uesr_status'] = 0;
                        break;
                    }
                }
            } else if ($v['status'] == 2) {

                foreach ($user_coupon as $value) {
                    if ($v['id'] == $value['coupon_id'] && $value['status'] < 2) {
                        $res[$k]['uesr_status'] = 0;
                        break;
                    }
                }
            }

        }
        return $res;
    }

    //订单列表
    public function get_order_list()
    {
        $uid = TokenService::getCurrentUid();
        $data = OrderModel::with(['OrderGoods.imgs'=>function($q){
            return $q->field('id,order_id,goods_id,goods_name,sku_name,price,num,total_price as goods_money,pic_id');
        }, 'Imgs'])->where(['user_id' => $uid])
            ->field('order_id,order_num,user_id,type,state,payment_state,shipping_money,shipment_state,order_money,activity_type,create_time')
            ->order('order_id desc')->select()->toArray();
        return $data;
    }

    //订单详情
    public function get_order_detail($id)
    {
        $uid = TokenService::getCurrentUid();
        $auth = UserModel::where('id', $uid)->value('web_auth_id');
        $where['order_id'] = $id;
        if ($auth == 0) {
            $where['user_id'] = $uid;
        }
        $data = OrderModel::with(['rate', 'ordergoods.imgs'])->where($where)->find(); //常规查询自动不包含软删除的数据
        if (!$data) {
            return app('json')->fail('获取订单数据失败');
        }
        $tui_data = TuiModel::where('order_id', $id)->select();
//        $data['rate'] = RateModel::where('order_id', $id)->find();
        if ($data['yz_code']) {
            $data['qrcode'] = (new QrcodeServer())->get_qrcode($data['yz_code']);
        }
        if ($tui_data) {
            $data['tui'] = $tui_data;
        }
        return $data;
    }
}