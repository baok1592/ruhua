<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/22 0022
 * Time: 14:52
 */

namespace app\validate;


use bases\BaseValidate;

class PtValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'start_time' => 'require',
        'end_time' => 'require',
        'pt_time' => 'require|number',
        'user_num' => 'require|number',
        'is_buy_num' => 'min:0',
        'buy_num' => 'min:0',
        'is_cou_tuan' => 'min:0',
        'is_cheng_tuan' => 'min:0',
        'cheng_tuan_num' => 'min:0',
        'is_sign_one' => 'min:0',
        'visible_time' => 'require|number',
    ];
}