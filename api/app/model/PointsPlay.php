<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/31 0031
 * Time: 10:15
 */

namespace app\model;


use bases\BaseModel;
use think\facade\Db;

class PointsPlay extends BaseModel
{
    public function award(){
        return $this->hasMany('PlayAward','type_id','id');
    }

    public static function editPlay($post){
        $id=$post['id'];
        $data['status'] =$post['status'];
        $data['use_points'] =$post['use_points'];
        $data['number'] =$post['number'];
        $award =$post['award'];
        try{
            Db::startTrans();
           $res= self::where('id',$id)->update($data);
            foreach ($award as $k=>$v){
                PlayAward::where('id',$k)->updata(['award'=>$v]);
            }
            Db::commit();
            return $res;
        }catch (\Exception $e){
            Db::rollback();
            return 0;
        }
    }
}