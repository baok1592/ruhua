<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22 0022
 * Time: 10:00
 */

namespace app\controller\common;

use app\model\Category as CategoryModel;
use app\validate\IDPostiveInt;
use bases\BaseController;
use services\QyFactory;

class Category extends BaseController
{
    /**
     * 获取X级分类信息
     * @param $id
     * @return \think\Collection|void
     */
    public function getCateLevel($id)
    {
        (new IDPostiveInt)->goCheck();
        return CategoryModel::getCategoryLevel($id);
    }

    /**
     * 获取所有分类信息
     * @return mixed
     */
    public function getAllCategory()
    {
        $article=(new QyFactory())->instance('UserService');
        $data=$article->get_category_list();
        return app('json')->success($data);
    }


    /**
     * 获取分类下所有子类与广告图
     * @param string $id
     * @return \think\response\Json
     */
    public function getCateChildImg($id="")
    {
        (new IDPostiveInt)->goCheck();
        return CategoryModel::getCateChildImg($id);
    }

}