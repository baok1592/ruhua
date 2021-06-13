<?php
namespace exceptions;

use think\exception\Handle;
use think\facade\Env;
use think\facade\Request;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    public function render($request, Throwable $e): Response
    {
        //instanceof 用于确定一变量是否属于某类实例：或是不是继承自某父类的子类的实例
        if ($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else {
            $this->code = 500;
            $this->msg = $e->getMessage();
            $this->errorCode = 999;
            //获取环境变量 Env::get('')
/*            if(Env::get('APP_DEBUG')) {
                $this->code = 500;
                $this->msg = $e->getMessage();
                $this->errorCode = 999;
            }else {
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->errorCode = 999;
            }*/
        }
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => Request::url()   //获取当前访问的URL
        ];
        return json($result, $this->code);

        // 其他错误交给系统处理
    }
}
