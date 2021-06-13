<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/30 0030
 * Time: 14:18
 */

namespace app\controller\cms;


use app\services\StatisticService;
use bases\BaseController;

class Statistic extends BaseController
{
    /**
     * mcms订单统计
     * @return mixed
     */
    public function getOrderNum(){
        return StatisticService::getOrderNum();
    }

    /**
     * cms首页数据
     * @return mixed
     */
    public function getCmsIndexData(){
        return StatisticService::getCmsIndexData();
    }

    /**
     * cms图表统计
     * @return mixed
     */
    public function getTableData(){
        return StatisticService::getTableData();
    }
    /**
     * cms订单统计销售额
     * @return mixed
     */
    public function getMoneyData(){
        return StatisticService::getMoneyData();
    }
    /**
     * cms订单统计订单数据
     * @return mixed
     */
    public function getOrderData(){
        return StatisticService::getOrderData();
    }
    /**
     * 待处理事项统计
     * @return mixed
     */
    public function remind(){
        return StatisticService::remind();
    }

}