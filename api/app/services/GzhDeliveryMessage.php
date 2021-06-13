<?php

namespace app\services;

use app\model\Template;
use app\model\User as UserModel;
use exceptions\BaseException;

//公众号模板消息
class GzhDeliveryMessage extends GzhMessage
{
    public function sendDeliveryMessage($data,$type,$ids='')
    {
        if (!$data) {
            return '';
        }
        $temp=Template::where('id',$type)->find();
        if($temp['state']!=1){
            return '';
        }
        $this->tplID = $temp['temp_id'];
        $this->prepareMessageData($data,$type);
        $openids=$this->getUserOpenID($type,$ids);
        parent::AccessToken();
        foreach ($openids as $k=>$v) {
            parent::sendMessage($v);
        }
    }

    private function prepareMessageData($order,$type)
    {
        switch ($type){
            case 1 :
                $dt = date('Y-m-d H:i:s',time());
                $data = [
                    'first' => [
                        'value' => '你有新的订单',
                    ],
                    'keyword1' => [
                        'value' => $dt
                    ],
                    'keyword2' => [
                        'value' => $order['ordergoods'][0]['goods_name']
                    ],
                    'keyword3' => [
                        'value' => $order['order_num']
                    ],
                    'remark' => [
                        'value' => '请登陆后台查看'
                    ]
                ];
                $this->data = $data;
                break;
            case 2:
                $data = [
                    'first' => [
                        'value' => '你有新的退款申请',
                    ],
                    'keyword1' => [
                        'value' => $order['order_num']
                    ],
                    'keyword2' => [
                        'value' => $order['order_money']
                    ],
                    'remark' => [
                        'value' => '请登陆后台查看'
                    ]
                ];
                $this->data = $data;
                break;
            case 3:
                $data = [
                    'first' => [
                        'value' => '拼团成功',
                    ],
                    'keyword1' => [
                        'value' => $order['goods_name']
                    ],
                    'keyword2' => [
                        'value' => $order['user']
                    ],
                    'keyword3' => [
                        'value' => $order['user_num']
                    ],
                    'remark' => [
                        'value' => '等待发货中'
                    ]
                ];
                $this->data = $data;
                break;
            case 4:
                $data = [
                    'first' => [
                        'value' => '拼团失败',
                    ],
                    'keyword1' => [
                        'value' => $order['goods_name']
                    ],
                    'keyword2' => [
                        'value' => $order['order_money']
                    ],
                    'keyword3' => [
                        'value' => $order['order_money']
                    ],
                    'remark' => [
                        'value' => ''
                    ]
                ];
                $this->data = $data;
                break;
            case 5:
                $dt = date('Y-m-d H:i:s',time());
                $data = [
                    'first' => [
                        'value' => '下单成功',
                    ],
                    'keyword1' => [
                        'value' =>$order['order_num']
                    ],
                    'keyword2' => [
                        'value' =>$order['ordergoods'][0]['goods_name']
                    ],
                    'keyword3' => [
                        'value' => $order['order_money']
                    ],
                    'keyword4' => [
                        'value' => '待发货'
                    ],
                    'keyword5' => [
                        'value' => $dt
                    ],
                    'remark' => [
                        'value' => ''
                    ]
                ];
                $this->data = $data;
                break;
        }
    }

    private function getUserOpenID($type,$ids)
    {
        if($type<=2){
            //web_auth_id：1为管理员,2为员工
            $openids = UserModel::where('web_auth_id',1)->column('openid_gzh');
            if (!$openids) {
                return [];
            }
            return $openids;
        }else{
            $openids = UserModel::where(['id'=>$ids])->column('openid_gzh');
            if (!$openids) {
                return [];
            }
            return $openids;
        }

    }

}