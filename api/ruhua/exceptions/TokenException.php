<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 9:17
 */

namespace exceptions;


use think\Exception;

class TokenException extends BaseException
{
    // HTTP 状态码 404,200
    public $code = 401;

    // 错误具体信息
    public $msg = 'token不存在';

    // 错误具体信息
    public $message = '用户权限有误';

    // 自定义的错误码
    public $errorCode = 10001;

}