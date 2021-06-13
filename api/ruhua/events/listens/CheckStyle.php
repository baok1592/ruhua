<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22 0022
 * Time: 17:08
 */

namespace events\listens;


use app\model\Goods as GoodsModel;
use exceptions\OrderException;

class CheckStyle
{
    /**
     * 检查是否为虚拟产品
     * @param OrderEvent $event
     * @return string
     * @throws
     */
    public function handle($event)
    {
        $post=$event;
        $ids = [];
        foreach ($post['json'] as $k => $v) {
            $ids[$k] = $v['goods_id'];
        }
        $check_res = $this->check_style($ids);
        if (!$check_res) {
            throw new OrderException(['msg'=>'订单中不能同时存在虚拟商品和实物商品']);
        }
    }

    /**
     * 检测商品是否都是虚拟或都是实物
     * @param $ids
     * @return int
     */
    public function check_style($ids)
    {
        $arr = GoodsModel::where(['goods_id'=>$ids])->column('style');
        $res = 1;
        $data=[];
        foreach ($arr as $v) {
            array_push($data,$v);
        }
        foreach ($data as $v) {
            if ($data[0] != $v) {
                $res = 0;
            }
        }
        return $res;
    }
}