<?php
namespace events;




use app\controller\common\Order;

class OrderEvent
{
    public $binds;
    public function __construct(Order $order)
    {
        $this->binds=$order;
    }
}