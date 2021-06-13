<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/26 0026
 * Time: 11:36
 */

namespace app\controller\cms;

use app\model\Order as OrderModel;
use app\model\Tui as TuiModel;
use app\services\TuiService;
use app\validate\IDPostiveInt;
use bases\BaseController;

class TuiManage extends BaseController
{
    /**
     * cms 获取所有退款信息
     * @return mixed
     */
    public function getTuiAll()
    {
        $res = TuiModel::select();
        foreach ($res as $k => $v) {
            $res[$k]['money'] = $v['money'];
        }
        return app('json')->success($res);
    }

    /**
     * 微信退款
     * @param string $id
     * @return mixed
     */
    public function TuiMoney($id='')
    {
        (new IDPostiveInt())->goCheck();
        return (new TuiService())->pay($id);
    }

    /**
     * 退款申请驳回
     * @param $id
     * @param $msg
     * @return mixed
     */
    public function TuiBoHui($id, $msg)
    {
        return OrderModel::TuiBoHui($id,$msg);
    }
}