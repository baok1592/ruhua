<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/29 0029
 * Time: 15:30
 */

namespace app\controller\cms;

use app\model\PointsRecord as PointsRecordModel;
use app\model\PointsConfig as PointsConfigModel;
use bases\BaseController;

class PointsManage extends BaseController
{
    /**
     * 查看积分详情
     * @return mixed
     */
    public function getPointsRecord(){
        $res=PointsRecordModel::with('user')->order('id','desc')->select();
        return app('json')->success($res);
    }
}