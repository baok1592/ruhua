<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/30 0030
 * Time: 11:24
 */

namespace app\validate;



use bases\BaseValidate;

class DiscountValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require',
        'start_time'=>'require',
        'end_time'=>'require',
        'time_rule'=>'require',
        'day_json'=>'min:0',
        'buy_rule'=>'require',
        'label'=>'require|min:2|max:5',
        'buy_num'=>'min:0',
        'goods_json'=>'require',
    ];
}