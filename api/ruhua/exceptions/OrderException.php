<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/25 0025
 * Time: 9:43
 */

namespace exceptions;


class OrderException extends BaseException
{
    public $code = 400;
    public $msg = '创建订单失败-back';
    public $errorCode = 1000;

    public function __construct($params = []){
        if( !is_array($params)){
            return ;
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
            $this->message = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}