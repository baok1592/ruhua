<?php

namespace events\listens;

use app\model\Goods as GoodsModel;
use exceptions\OrderException;

class CheckStock
{

    public function handle($event)
    {
        $post = $event;
        $this->checkStock($post['json']);
    }

    /**
     * 检测库存
     * @param $data
     * @return int
     * @throws OrderException
     */
    public static function checkStock($data)
    {
        GoodsModel::checkStock($data);
    }
}