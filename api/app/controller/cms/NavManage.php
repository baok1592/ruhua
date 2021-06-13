<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/14 0014
 * Time: 15:26
 */

namespace app\controller\cms;


use app\model\Nav as NavModel;
use app\validate\IDPostiveInt;
use bases\BaseController;

class NavManage extends BaseController
{
    /**
     * cms 新增导航
     * @return int
     */
    public function addNav()
    {
        $rule=[
            'nav_name'=> 'require',
            'img_id'=> 'require',
            'url'=> 'require',
        ];
        $post=input('post.');
        $this->validate($post,$rule);
        return NavModel::addNav($post);
    }

    /**
     * cms更新导航
     * @param $id
     * @return int|\think\response\Json
     */
    public function editNav($id='')
    {
        (new IDPostiveInt)->goCheck();
        $post=input('post.');
        return NavModel::editNav($post,$id);
    }

    /**
     * cms 删除导航
     * @param $id
     * @return \think\Collection|void
     */
    public function deleteNav($id='')
    {
        (new IDPostiveInt())->goCheck();
        return NavModel::deleteNav($id);
    }


    /**
     * 获取所有导航
     * @return \think\response\Json
     */
    public function getNav()
    {
        $data=NavModel::where('is_visible',1)->order('sort desc')->select();
        return app('json')->success($data);
    }

    /**
     * 更新导航排序
     * @return int
     */
    public function setSort()
    {
        $arr=input('post.');
        return NavModel::setSort($arr);
    }
}