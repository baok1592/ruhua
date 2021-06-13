<?php

namespace app\validate;


use bases\BaseValidate;

class FavValidate extends BaseValidate
{
    protected $rule = [
        'fav_id' => 'require|isPositiveInteger',//收藏品的id
        'type' => 'require|is_type',    //商品goods或商铺shop
        'price' => 'require',    //商品价格,商铺为0
//        'img_id' => 'require|isPositiveInteger' //收藏品的图片
    ];

    public function is_type($v)
    {
        if($v=='goods' || $v=='shop'){
            return true;
        }
        return false;
    }
    protected $message  =   [
        'type.is_type' => 'type只能为goods或shop'
    ];
}
