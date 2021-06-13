<?php
/**
 * 如花拼团开源系统 -- 永久免费
 * =========================================================
 * 官方网址：http://www.phps.shop
 * 作者：光爵【API + 后台】 、 小草【小程序 + WAP】
 * QQ 交流群：728615087
 * Version：1.0.0
 */

namespace app\services;

use app\lib\enum\OrderEnum;
use GzhPay\lib\WxPayNotify;
use think\Db;
use think\Exception;
use think\facade\Log;
use app\model\Order as OrderModel;
use app\model\Goods as GoodsModel;
use app\model\GoodsSku as GoodsSkuModel;

class GzhNotifyService extends WxPayNotify
{
    //继承覆盖
    public function LogAfterProcess($xmlData)
    {

        Log::error("call back， return xml:" . $xmlData);
        return;
    }

    //继承覆盖
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        //TODO 1、进行参数校验
        if (!array_key_exists("return_code", $data)
            || (array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")
        ) {
            //TODO失败,不是支付成功的通知
            //如果有需要可以做失败时候的一些清理处理，并且做一些监控
            $msg = "异常异常";
            Log::error("异常异常");
            return false;
        }
        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            Log::error("输入参数不正确");
            return false;
        }

        //TODO 2、进行签名验证
        try {
            $checkResult = $objData->CheckSign($config);
            if ($checkResult == false) {
                //签名错误
                Log::error("签名错误...");
                return false;
            }
        } catch (Exception $e) {
            Log::error("签名验证" . json_encode($e));
        }

        //TODO 3、处理业务逻辑
        Log::error("执行回调-自定义方法：");
        $notify=new NotifyService();
        $res=$notify->NotifyEditOrder($data['out_trade_no']);
        if (!$res) {
            return false;
        }
        return true;
    }

    //更新订单状态
    public function upOrder($orderNo)
    {
        Db::startTrans();
        try {
            //Lock方法是用于数据库的锁机制;
            $order = OrderModel::with('ordergoods')->where('order_num', $orderNo)->lock(true)->find();
            if ($order['payment_state'] == 0 && $order['state'] == 0) {
                OrderModel::where('order_id', $order['order_id'])->update(['payment_state' =>1,'pay_time'=>time()]);  //更新订单状态为已支付
                event('ReduceStock',$order);//扣除库存89
                event('SendGzhDeliveryMessage',$order);//公众号发送模板消息通知管理员
                Db::commit();
            }
        } catch (Exception $ex) {
            Db::rollback();
            Log::error('更新订单失败:' . $ex);
            return false;
        }
        return true;
    }

    //订单更新状态为：
    private function updateOrderStatus($orderID, $success)
    {
        OrderModel::where('order_id', $orderID)->update(['payment_state' =>1,'pay_time'=>time()]);
    }

}