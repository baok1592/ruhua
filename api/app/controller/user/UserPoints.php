<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/30 0030
 * Time: 14:05
 */

namespace app\controller\user;


use app\model\PointsRecord as PointsRecordModel;
use app\model\User as UserModel;
use app\services\TokenService;
use bases\BaseController;

class UserPoints extends BaseController
{
    /**
     * 查看我的积分详情
     * @return mixed
     */
    public function getPoints(){
        $uid=TokenService::getCurrentUid();
        $res=PointsRecordModel::where('user_id',$uid)->order('id','desc')->select();
        return app('json')->success($res);
    }

    /**
     * 获取用户的积分
     * @return mixed
     */
    public function getPointsNum(){
        $uid=TokenService::getCurrentUid();
        $res=UserModel::where('id',$uid)->value('points');
        return app('json')->success($res);
    }
}