<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/9 0009
 * Time: 10:10
 */

namespace app\controller\install;


use bases\BaseCommon;
use bases\BaseController;
use think\facade\Request;

class UpDate extends BaseController
{

    /**
     * 获取更新包
     * @return mixed
     */
    public function getRhFile(){
//        $res=curl_post('192.168.1.118:8092/project/user/down_rh',['domain'=>Request::domain()]);
        $res= (new BaseCommon())->curl_post('http://authorization.ruhuashop.com/project/user/down_rh',['domain'=>Request::domain()]);
        if(!$res){
            return app('json')->fail();
        }
        $res=json_decode($res,true);
        if($res['result_code']=='fail'){
            return app('json')->fail($res['err_code_des']);
        }
        if($res['result_code']=='success'){
            return app('json')->success($res);
        }
    }

    /**
     * 获取版本号
     * @return mixed
     */
    public function getVersion(){
        $res= (new BaseCommon())->curl_post('http://authorization.ruhuashop.com/project/user/get_version',['domain'=>Request::domain()]);
        if(!$res){
            return app('json')->fail();
        }
        $res=json_decode($res,true);
        if($res['result_code']=='fail'){
            return app('json')->fail($res['err_code_des']);
        }
        if($res['result_code']=='success'){
            return app('json')->success($res);
        }
    }

    /**
     * 获取
     * @return mixed
     */
    public function getResource(){
        $res= (new BaseCommon())->curl_post('http://authorization.ruhuashop.com/project/user/get_resource',['domain'=>Request::domain()]);
        if(!$res){
            return app('json')->fail();
        }
        $res=json_decode($res,true);
        if($res['result_code']=='fail'){
            return app('json')->fail($res['err_code_des']);
        }
        if($res['result_code']=='success'){
            return app('json')->success($res);
        }
    }

    public function test(){
        $res=(new BaseCommon)->curl_get('http://192.168.1.118:8092/project/user/test_refer');
        return $res;
    }
}