<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/1 0001
 * Time: 8:53
 */

namespace subscribes;


use app\model\FxAgent as FxAgentModel;
use app\model\FxBind as FxBindModel;
use app\model\FxBind;
use app\model\FxRecord;

/**
 * 分销事件
 * Class ProductSubscribe
 * @package crmeb\subscribes
 */
class FxSubscribes
{

    public function handle()
    {

    }

    /**
     * 添加商品分销提现记录
     * @param $event
     */
    public function onAddGoodsFxRecord($event){
        list($oid,$uid)=$event;
        if(app('system')->getValue('fx_status') != 0) {
            FxRecord::addRecord($oid, $uid);
        }
    }

    /**
     * 添加VIP分销提现记录
     * @param $event
     */
    public function onAddVipFxRecord($event){
        list($oid,$uid)=$event;
        if(app('system')->getValue('fx_status') != 0) {
            FxRecord::addVipRecord($oid, $uid);
        }
    }
}