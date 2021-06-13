<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21 0021
 * Time: 9:13
 */

namespace app\model;


use bases\BaseModel;
use think\model\concern\SoftDelete;

class OrderGoods extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden = ['delete_time'];

    //关联订单商品模型
//    public function orders()
//    {
//        return $this->belongsTo('Order','order_id','order_id');
//    }

//    //关联规格模型
//    public function sku()
//    {
//        return $this->hasMany('GoodsSku','goods_id','goods_id');
//    }
//
    //关联图片
    public function imgs()
    {
        return $this->belongsTo('Image','pic_id','id')->field('id,url');
    }
//
//    //关联团单
//    public function item()
//    {
//        return $this->belongsTo('item','item_id','id');
//    }
//
//    public function setImgsAttr($v)
//    {
//        return $v['url'];
//    }

}