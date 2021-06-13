<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31 0031
 * Time: 15:36
 */

namespace app\controller\user;


use app\model\FxAgent as FxAgentModel;
use app\model\FxBind as FxBindModel;
use app\services\TokenService;
use bases\BaseController;

class Agent extends BaseController
{
    /**
     * 申请代理商
     * @return mixed
     */
    public function addAgent(){
        $uid=TokenService::getCurrentUid();
        $sfm=input('post.sfm');
        return FxAgentModel::addAgent($uid,$sfm);
    }

    /**
     * 添加绑定
     * @return mixed
     */
/*    public function addBind(){
        $uid=TokenService::getCurrentUid();
        $rule=[
            'sfm'=>'require',
        ];
        $post=input('post.');
        $this->validate($post,$rule);
       return FxBindModel::addBind($post,$uid);
    }*/
}