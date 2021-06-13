<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18 0018
 * Time: 14:15
 */

namespace app\controller\user;


use app\model\FxAgent as FxAgentModel;
use app\model\FxBind as FxBindModel;
use app\model\FxRecord as FxRecordModel;
use app\services\TokenService;
use bases\BaseController;

class UserFx extends BaseController
{
    /**
     * 用户查看分销收入统计
     * @return mixed
     */
    public function getFxData(){
        $uid=TokenService::getCurrentUid();
        $agent=FxAgentModel::where('user_id',$uid)->find();
        if(!$agent){
            return app('json')->fail('不是代理商');
        }
        return FxRecordModel::getFxMoneyData($uid);
    }

    /**
     * 用户查看分销收入明细
     * @return mixed
     */
    public function getFxRecord(){
        $uid=TokenService::getCurrentUid();

        $agent=FxAgentModel::where('user_id',$uid)->find();
        if(!$agent){
            return app('json')->fail('不是代理商');
        }
        return FxRecordModel::userGetRecord($uid);
    }

    /**
     * 查看排名
     * @return mixed
     */
    public function getFxRank(){
        $uid=TokenService::getCurrentUid();
        $agent=FxAgentModel::where('user_id',$uid)->find();
        if(!$agent){
            return app('json')->fail('不是代理商');
        }
        return FxRecordModel::getFxRank($uid);
    }

    public function getBindUser(){
        $uid=TokenService::getCurrentUid();
        $agent=FxAgentModel::where('user_id',$uid)->find();
        if(!$agent){
            return app('json')->fail('不是代理商');
        }
        return FxBindModel::getBindUser($uid);
    }

    /**
     * 提交提现申请
     * @return mixed
     */
    public function applyTx(){
        $uid=TokenService::getCurrentUid();
        $ids=input('post.ids');
        if(!$ids) return app('json')->fail('ids必填');
        return FxRecordModel::userApplyTx($uid,$ids);
    }

}