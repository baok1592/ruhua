<?php
/**
 * 如花拼团开源系统 -- 永久免费
 * =========================================================
 * 官方网址：http://www.phps.shop
 * 作者：光爵【API + 后台】 、 小草【小程序 + WAP】
 * QQ 交流群：728615087
 * Version：1.0.0
 */

namespace enum;

//自定义枚举类
class OrderEnum
{
    // 待支付
    const UNPAYID = 0;

    // 已支付
    const PAYID = 1;

    // 已发货
    const DELIVERED = 1;

    // 已支付，但库存不足
    const PAYID_BUT_OUT_OF = 3;
}