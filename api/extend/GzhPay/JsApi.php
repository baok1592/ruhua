<?php

namespace GzhPay;

use app\services\TokenService;
use GzhPay\lib\WxPayApi;
use GzhPay\lib\WxPayUnifiedOrder;
use think\Exception;
use think\facade\Log;
use GzhPay\lib\WxPayDataBase;
use exceptions\BaseException;


class JsApi
{

    public static function gzh_pay($openid, $order_data, $gzh)
    {
        Log::error('start');
        $jsApiParameters = "";
        try {
            $tools = new JsApiPay();
            new WxPayDataBase();
            $input = new WxPayUnifiedOrder();
            $input->SetBody($gzh['web_name']);
            $input->SetAttach("test");
            $input->SetOut_trade_no($order_data['order_num']);
            $input->SetTotal_fee($order_data['order_money'] * 100);
            $input->SetTime_start(date("YmdHis"));
            //$input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag("test");
            $input->SetNotify_url($gzh['api_url'] . "/order/back");
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openid);
            $config = new WxPayConfig();
            $order = WxPayApi::unifiedOrder($config, $input);
            $jsApiParameters = $tools->GetJsApiParameters($order);
        } catch (Exception $e) {
            Log::error(json_encode($e));
            throw new BaseException(['msg' => '支付错误']);
        }
        return $jsApiParameters;
    }


}

