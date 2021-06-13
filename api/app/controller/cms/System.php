<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 8:52
 */

namespace app\controller\cms;


use app\model\SysConfig as SysConfigModel;
use app\model\Template;
use bases\BaseController;

class System extends BaseController
{
    /**
     * 获取商城配置信息
     * @param $type
     * @return \think\response\Json
     */
    public function GetConfig($type)
    {
        $data=SysConfigModel::where(['type'=>$type])->order('switch','desc')->select();
        return app('json')->success($data);
//        return json($data);
    }

    /**
     * 修改配置信息
     * @return int
     */
    public function edit_config()
    {
        $post = input('post.');
        $res = (new SysConfigModel())->saveAll($post);
        if(!$res){
           return app('json')->fail();
        }
        return app('json')->success();
//        return 1;
    }

    /**
     * 修改模板消息
     * @return mixed
     */
    public function editTemplate(){
        $post=input('post.');
       return Template::editTemplate($post);
    }
}