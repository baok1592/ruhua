<?php

namespace exceptions;


use think\Exception;

class BaseException extends Exception
{
    // HTTP 状态码 404,200
    public $code = 400;
    // 错误具体信息
    public $msg = '未知错误--Base';
    // 自定义的错误码
    public $errorCode = 9999;

    public function __construct($params = []){
        if( !is_array($params)){
           return ;
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }

}