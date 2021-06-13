<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/13 0013
 * Time: 10:36
 */

namespace app\controller\install;

use app\model\SysConfig as SystemModel;
use bases\BaseController;

class Check extends BaseController
{
    public function index()
    {
        try{
            $res=SystemModel::find(1);
        }catch (\Exception $e){
            return app('json')->fail('数据库连接错误');
        }
        if($res){
            return app('json')->success('安装成功');
        }
    }
}