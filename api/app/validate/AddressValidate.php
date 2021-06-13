<?php

namespace app\validate;


use bases\BaseValidate;

class AddressValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|isNotEmpty',
        'province' => 'require|isNotEmpty', //省
        'city' => 'require|isNotEmpty', //市
        'county' => 'require|isNotEmpty', //区
        'detail' => 'require|isNotEmpty', //详细
        'areacode'=>'min:0'
    ];

    protected $message = [
      'mobile.isMobile' => "手机号有误",
      'city' => "地区有误",
      'detail' => "详细地址有误"
    ];
}
