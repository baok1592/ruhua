<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 14:27
 */

namespace app\model;


use bases\BaseModel;

class BannerItem extends BaseModel
{
    protected $hidden = ['update_time','delete_time'];

    public function imgs()
    {
        return $this->belongsTo('Image','img_id','id');
    }
    public function banner()
    {
        return $this->belongsTo('Banner','banner_id','id');
    }
    public function setImgsAttr($v)
    {
        return $v['url'];
    }

    /**
     * 添加广告
     * @param $post
     * @return mixed
     */
    public static function addBanner($post){
        $res = self::create($post);
        if(!$res){
            return app('json')->fail();
        }
        return app('json')->success($res['id']);
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