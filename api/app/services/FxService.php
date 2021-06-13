<?php

namespace app\services;

use app\model\FxGoods;
use app\model\OrderGoods as OrderGoodsModel;
use exceptions\OrderException;

class FxService
{
    /**
     * 计算一个订单的分销佣金
     * 未使用此方法，用的另一种方法
     */
    public static function cpu_yj($goods)
    {
        $arr=[];
        $pros=[];
        $total=0;
        foreach ($goods as $k => $v) {
            $arr[]=$v['goods_id'];
            $pros[$v['goods_id']]=[];
            $pros[$v['goods_id']]['num']=$v['num'];
        }
        $list=FxGoods::where([['goods_id','in',$arr]])->field('goods_id,price')->select();
        foreach ($list as $k=>$v){
            $price=$pros[$v['goods_id']]['num']*$v['price'];
            $total+=$price;
        }
        return $total;
    }

}