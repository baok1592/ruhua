<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/31 0031
 * Time: 10:14
 */

namespace app\controller\cms;

use app\model\PointsPlay as PointsPlayModel;
use bases\BaseController;
/**
 * 玩法管理
 * Class PointsPlayManage
 * @package app\controller\cms
 */
class PointsPlayManage extends BaseController
{
    /**
     * 获取所有的玩法
     * @return mixed
     */
    public function getAllPlay(){
       $res= PointsPlayModel::select();
        return app('json')->success($res);
    }

    /**
     * 获取某个玩法的详情
     * @param $id
     * @return mixed
     */
    public function getOnePlay($id){
        $res=PointsPlayModel::with('award')->where('id',$id)->find();
        return app('json')->success($res);
    }

    /**
     * 修改玩法规则
     * @return mixed
     */
    public function editPlay(){
        $post=input('post.');
        $this->validate($post,['id'=>'require','status'=>'require','use_points'=>'require','number'=>'require','award'=>'require|array']);
        $res=PointsPlayModel::editPlay($post);
        if($res){
            return app('json')->success();
        }
        return app('json')->fail();
    }
}