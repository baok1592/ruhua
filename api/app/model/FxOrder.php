<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31 0031
 * Time: 16:34
 */

namespace app\model;


use bases\BaseModel;

class FxOrder extends BaseModel
{
    /**
     * 删除订单
     */
    public static function deleteOrder(){
        $time=time()-60*60;
        self::where('order_status',1)->whereTime('create_time','<',$time)->delete();
    }
}