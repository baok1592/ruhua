<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/22 0022
 * Time: 13:00
 */

namespace app\controller\cms;


use app\model\Pt as PtModel;
use app\model\PtGoods as PtGoodsModeL;
use app\services\NotifyService;
use app\validate\IDPostiveInt;
use app\validate\PtValidate;
use bases\BaseController;

class PtManage extends BaseController
{
    /**
     * 添加拼团活动
     * @return mixed
     */
    public function addPt()
    {
        (new PtValidate())->goCheck();
        $post = input('post.');
        return PtModel::addPt($post);
    }

    /**
     * 修改拼团活动
     */
    public function editPt()
    {
        $post = input('post.');
        return PtModel::editPt($post);
    }

    /**
     * 删除拼团活动
     * @param $id
     * @return mixed
     */
    public function deletePt($id = '')
    {
        (new IDPostiveInt())->goCheck();
        return PtModel::deletePt($id);
    }

    /**
     * 获取拼团活动
     * @return mixed
     */
    public function getPt()
    {
        $res = PtModel::with('ptGoods.goods')->select()->toArray();
        foreach ($res as $k => $v) {
            $res[$k]['start_time'] = date('Y-m-d', strtotime($v['start_time']));
            $res[$k]['end_time'] = date('Y-m-d', strtotime($v['end_time']));
            foreach ($v['pt_goods'] as $kk => $vv) {
                if (!$vv['goods']) {
                    unset($res[$k]['pt_goods'][$kk]);
                }
            }
        }
        return app('json')->success($res);
    }

    /**
     *  获取拼团商品
     * @param $type
     * @return mixed
     */
    public function getPtGoods($type)
    {
        $res = PtGoodsModeL::getAllPtGoods($type);
        return app('json')->success($res);
    }

    public function NotifyPtOrder()
    {
        $order_num = input('post.order_num');
        $notify = new NotifyService();
        $res = $notify->NotifyEditOrder($order_num);
        return app('json')->success($res);
    }
}