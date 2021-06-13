<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 13:42
 */

namespace app\model;


use bases\BaseModel;

class Favorites extends BaseModel
{

    public function shopinfo()
    {
        return $this->belongsTo('Shop','fav_id','shop_id');
    }
    public function product()
    {
        return $this->belongsTo('goods','fav_id','goods_id');
    }
    public function favimg()
    {
        return $this->belongsTo('Image','img_id','id');
    }

    /**
     * 添加收藏
     * @param $data
     * @return mixed
     */
    public static function addFavorites($data){
        $res=self::where(['uid'=>$data['uid'],'fav_id'=>$data['fav_id']])->find();
        if($res){
            return app('json')->fail('已收藏过了');
        }
        $res2=self::create($data);
        if(!$res2){
            return app('json')->fail();
        }
        return app('json')->success();
    }
    /**
     * 查询某商品是否已收藏
     * @param $id
     * @param $uid
     * @param $type
     * @return int
     */
    public static function getFavTypeByID($id,$uid,$type)
    {
        $where['type']=$type;
        $where['fav_id']=$id;
        $where['uid']=$uid;
        $count=self::where($where)->count();
        if($count<1){
            return 0;
        }
        return 1;
    }

    /**
     * 获取该用户所有收藏
     * @param $uid
     * @return array
     */
    public static function getFavoriteByUid($uid)
    {
        $data=self::with(['product'=>function($query){
            $query->field('goods_id,goods_name,price,img_id');
        }])->where('uid',$uid)->select();
        $api_url = SysConfig::where('key','api_url')->value('value');
        foreach ($data as $k=>$v){
            $url=Image::where('id',$v['product']['img_id'])->value('url');
            $data[$k]['imgs']=$url?$url:'';
        }
        if(!$data){
            return app('json')->fail('无收藏数据或查询失败');
        }
        $arr=[];
        foreach ($data as $k=>$v){
            if($v['type']=='goods'){
                $arr['goods'][]=$v;
            }else{
                $arr['shop'][]=$v;
            }
        }
        return app('json')->success($arr);
    }

}