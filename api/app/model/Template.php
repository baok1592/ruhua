<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/28 0028
 * Time: 14:46
 */

namespace app\model;


use bases\BaseModel;

class Template extends BaseModel
{
    /**
     * 修改模板
     * @param $post
     * @return mixed
     */
    public static function editTemplate($post){
        if(!is_array($post)){
            return app('json')->fail();
        }
        foreach ($post as $k=>$v){
            self::update(['temp_id'=>$v],['id'=>$k]);
        }
        return app('json')->success();
    }
}