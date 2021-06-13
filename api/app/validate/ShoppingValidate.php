<?php

namespace app\validate;


use bases\BaseValidate;

class ShoppingValidate extends BaseValidate
{
    protected $rule = [
        'order_from' => 'require', //订单来源,0:小程序,1:wap
        'payment_type' => 'require', //支付方式 wx
        'msg' => 'chsAlphaNum',  //地址ID
        'json'=>'require',
        'total_price'=>'require',
        'coupon_id'=>'min:0',
    ];
    protected $message = [
        'msg.chsAlphaNum'        => '备注只能是汉字、字母和数字',
    ];
}
