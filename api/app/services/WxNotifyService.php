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

use think\facade\Log;
use WxPay\WxPayNotify;

class WxNotifyService extends WxPayNotify
{
    //异步接收微信回调，更新订单状态
    public function NotifyProcess($data, &$msg)
    {
        Log::error('进入NotifyProcess');
        if ($data['result_code'] == 'SUCCESS') { 
            $orderNo = $data['out_trade_no'];
            $notify=new NotifyService(); 
            return $notify->NotifyEditOrder($orderNo);
        } else { 
            return true;
        }
    }


}