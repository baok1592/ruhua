<?php

namespace interfaces;

Interface RoleInterface
{

    public function get_article_list();//文章、公告列表
    public function get_banner_list();//广告列表
    public function get_coupon_list();//优惠券列表
    public function get_category_list();//分类列表
    public function get_order_list();//订单列表
    public function get_order_detail($id);//订单详情

}