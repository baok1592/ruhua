<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14 0014
 * Time: 15:49
 */

namespace app\model;


use bases\BaseModel;

class Group extends BaseModel
{
    /**
     * 添加分组
     * @return int
     */
    public static function addGroup($post)
    {
        $data['name'] = $post['name'];
        $data['rule'] = implode(',',$post['rule_ids']);
        $res = self::create($data);
        if($res){
            return app('json')->success($res['id']);
        }else{
            return app('json')->fail();
        }
    }

    /**
     * 修改分组
     * @param $post
     * @return int
     */
    public static function editGroup($post)
    {
        $id = $post['id'];
        $data['name'] = $post['name'];
        $data['rule'] = implode(',',$post['rule_ids']);
        $res = self::update($data, ['id' => $id]);
        if($res){
            return app('json')->success();
        }else{
            return app('json')->fail();
        }
    }
}