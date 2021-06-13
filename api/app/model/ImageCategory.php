<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/12 0012
 * Time: 13:19
 */

namespace app\model;


use bases\BaseModel;

class ImageCategory extends BaseModel
{
    /**
     * 添加分类
     * @param $data
     * @return mixed
     */
    public static function addCategory($data)
    {
        $res = self::create($data);
        if ($res) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 删除分类
     * @param $id
     * @return mixed
     */
    public static function deleteCategory($id)
    {
        $res = self::where('id', $id)->delete();
        if ($res) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }
}