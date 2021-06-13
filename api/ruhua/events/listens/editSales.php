<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31 0031
 * Time: 8:49
 */

namespace events\listens;


use app\model\Goods as GoodsModel;
use app\model\Order as OrderModel;
use exceptions\OrderException;

class editSales
{
    public function handle($event)
    {
        $post = $event;
        $this->editSales($post);
        return '';
    }

    /**
     * 修改销量
     * @param $data
     * @return int
     * @throws OrderException
     */
    public function editSales($data)
    {
        $order = OrderModel::with('ordergoods')->where('order_id', $data['order_id'])->find();
        $goods = $order['ordergoods'];
        foreach ($goods as $k => $v) {
            $res = GoodsModel::where('goods_id', $v['goods_id'])->find();
            $res['sales'] += 1;
            $state = $res->save();
            if (!$state) {
                throw new OrderException();
            }
        }
    }

}