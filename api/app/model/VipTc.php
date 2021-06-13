<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/9 0009
 * Time: 13:39
 */

namespace app\model;


use bases\BaseModel;

class VipTc extends BaseModel
{

    /**
     * 添加会员套餐
     * @param $post
     * @return mixed
     */
    public static function addVipTc($post){
        $res=self::create(['price'=>$post['price'],'title'=>$post['title'],'day_num'=>$post['day_num']]);
        return $res?app('json')->success():app('json')->fail();
    }

    /**
     * 修改套餐
     */

}