<?php

namespace app\services;

use app\model\User as UserModel;
use exceptions\BaseException;
use think\facade\Log;

//小程序模板消息
class DeliveryMessage extends WxMessage
{
    const DELIVERY_MSG_ID ='aNabrJLz02ep0JPug8XqN7Pj9G_-_YGn48u0ey2Zbl0';

    public function sendDeliveryMessage($order, $tplJumpPage = '')
    {
        if (!$order) {
            throw new BaseException();
        }
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($order);
        //$this->emphasisKeyWord='keyword2.DATA';
        $openid=$this->getUserOpenID($order->user_id);
        parent::sendMessage($openid);
    }

    private function prepareMessageData($order)
    {
        $dt = new \DateTime();
        $data = [
            'keyword1' => [
                'value' => $order->order_num
            ],
            'keyword2' => [
                'value' => $order->goods_name,
                'color' => '#27408B'
            ],
            'keyword3' => [
                'value' => $order->num
            ],
            'keyword4' => [
                'value' => $order->order_money
            ] 
        ];
        $this->data = $data;
    }

    private function getUserOpenID($uid)
    {
        $user = UserModel::get($uid);
        if (!$user) {
            throw new BaseException();
        }
        return $user->openid;
    }

}