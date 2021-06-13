<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/7 0007
 * Time: 15:50
 */

namespace app\controller\common;


use app\services\TaskService;
use bases\BaseController;

class Task extends BaseController
{

    /**
     * 1天执行一次的定时任务 如：分销自动打款
     *  @return mixed
     */
    public function getDayRefresh()
    {
        return (new TaskService())->TxTask();
    }

    /**
     * 3小时定时任务 删除过期优惠券、未购买会员的订单等
     * @return mixed
     */
    public function getRefresh()
    {
        return (new TaskService())->DayTask();
    }

    /**
     * 15分钟定时任务 关闭超时为支付和未拼团成功的订单
     * @return mixed
     */
    public function getOrderTask()
    {
        return (new TaskService())->MinTask();
    }

    /**
     * 查看系统自动关闭订单运行状态
     * @return mixed
     */
    public function checkLoopTask()
    {
        return (new TaskService())->checkLoopTask();
    }

    /**
     * 循环任务 关闭超时为支付和未拼团成功的订单
     * @return mixed
     */
    public function getLoopTask()
    {
        return (new TaskService())->loopTask('174369');
    }
}