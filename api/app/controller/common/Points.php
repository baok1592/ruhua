<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/30 0030
 * Time: 12:51
 */

namespace app\controller\common;


use app\model\PointsRecord as PointsRecordModel;
use app\services\TokenService;
use bases\BaseController;

class Points extends BaseController
{
    /**
     * 签到添加积分
     * @return mixed
     */
    public function signAddPoints(){
        $uid=TokenService::getCurrentUid();
        return (new PointsRecordModel)->sign($uid);
    }
}