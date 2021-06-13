<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 10:37
 */

namespace app\controller\cms;

use app\model\Category as CategoryModel;
use app\validate\IDPostiveInt;
use bases\BaseCommon;
use bases\BaseController;
use services\QyFactory;

class CategoryManage extends BaseController
{
    /**
     * cms 新增分类
     * @param $form
     * @return int
     */
    public function addCategory($form)
    {
        $rule=[
            'category_name'=> 'require|min:2',
            'pid'=> 'require|number'
        ];
        $this->validate($form,$rule);
        return CategoryModel::addCategory($form);
    }

    /**
     * cms更新分类
     * @param $form
     * @return int|\think\response\Json
     */
    public function editCategory($form)
    {
        return CategoryModel::editCategory($form);
    }

    /**
     * cms 删除分类
     * @param $id
     * @return \think\Collection|void
     */
    public function deleteCategory($id)
    {
        (new IDPostiveInt())->goCheck();
        return CategoryModel::deleteCategory($id);
    }


    /**
     * cms 获取所有分类并排好序，包括隐藏
     * @return \think\response\Json
     */
    public function getCateSort()
    {
        $article=(new QyFactory())->instance('CmsService');
        $data=$article->get_category_list();
        $data = (new BaseCommon())->subTree($data);
        return app('json')->success($data);
    }

    /**
     * 更新分类排序
     * @return int
     */
    public function setSort()
    {
        $arr=input('post.');
        return CategoryModel::setSort($arr);
    }



}