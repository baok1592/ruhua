<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/26 0026
 * Time: 10:25
 */

namespace app\controller\common;


use app\model\PtItem;
use bases\BaseController;
use app\services\PtOrderService;
use app\services\TokenService;

class PtOrder extends BaseController
{

    /**
     * 队长创建拼团订单
     * @return mixed
     */
    public function createPtItemOrder()
    {
        $post = input('post.');
        $this->validate($post, ['goods_id' => 'require', 'num' => 'require', 'order_from' => 'require',
            'payment_type' => 'require', 'msg' => 'chsAlphaNum', 'total_price' => 'require', 'is_captain_sign' => 'require',]);
        $uid = TokenService::getCurrentUid();
        if ($post['payment_type'] == 'wx') {
            $openid = TokenService::getCurrentTokenvar('openid');
            return (new PtOrderService())->createGzhOrder($post, $uid, $openid, 1);
        }
        if ($post['payment_type'] == 'xcx') {
            return (new PtOrderService())->createWxOrder($post, $uid, 1);
        }
    }

    /**
     * 团员创建拼团订单
     * @return mixed
     */
    public function createPtOrder()
    {
        $post = input('post.');
        $this->validate($post, ['item_id' => 'require', 'goods_id' => 'require', 'num' => 'require', 'order_from' => 'require',
            'payment_type' => 'require', 'msg' => 'chsAlphaNum', 'total_price' => 'require', 'is_captain_sign' => 'require',]);
        $uid = TokenService::getCurrentUid();
        if ($post['payment_type'] == 'wx') {
            $openid = TokenService::getCurrentTokenvar('openid');
            return (new PtOrderService())->createGzhOrder($post, $uid, $openid, 2);
        }
        if ($post['payment_type'] == 'xcx') {
            return (new PtOrderService())->createWxOrder($post, $uid, 2);
        }
    }

    /**
     * 获取拼团队伍
     * @param $id
     * @return \think\response\Json
     */
    public function getPtItem($id)
    {
        $res = PtItem::getItem($id);
        return $res;
    }

    /**
     * 获取某个拼团队伍
     * @param $id
     * @return \think\response\Json
     */
    public function getOnePtItem($id)
    {
        $res = PtItem::getoneItem($id);
        return $res;
    }
}