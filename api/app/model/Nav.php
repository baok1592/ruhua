<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/14 0014
 * Time: 15:22
 */

namespace app\model;


use bases\BaseModel;

class Nav extends BaseModel
{
    /**
     * 添加分类
     * @param $post
     * @return int
     */
    public static function addNav($post)
    {
        $post['sort'] = 0;
        $post['is_visible'] = 1;
        $res=self::where('nav_name',$post['nav_name'])->find();
        if($res){
            return app('json')->fail('重复添加');
        }
        $res = self::create($post);
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 修改分组
     * @param $post
     * @return int
     */
    public static function editNav($post,$id)
    {
        $res = self::where(['id' => $id])->update($post);
        if($res){
            return app('json')->success();
        }
        return app('json')->fail();
    }

    /**
     * 删除分组
     * @param $id
     * @return int
     */
    public static function deleteNav($id)
    {
        $result = self::where(['id' => $id])->delete();
        if (!$result) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 排序
     * @param $arr
     * @return int
     */
    public static function setSort($arr){
        if(!is_array($arr)){
            return app('json')->fail();
        }
        foreach ($arr as $k=>$v){
            self::update(['sort'=>$v],['id'=>$k]);
        }
        return app('json')->success();
    }
}