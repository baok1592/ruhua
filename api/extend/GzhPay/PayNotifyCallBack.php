<?php

namespace GzhPay;

use GzhPay\lib\WxPayApi;
use GzhPay\lib\WxPayDataBase;
use GzhPay\lib\WxPayNotify;
use GzhPay\lib\WxPayOrderQuery;
use think\Exception;
use think\facade\Log;

new WxPayDataBase;

class PayNotifyCallBack extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        new WxPayDataBase();
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);

        $config = new WxPayConfig();
        $result = WxPayApi::orderQuery($config, $input);
        Log::error(json_encode($result));
        if (array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS"
        ) {
            return true;
        }
        return false;
    }

    /**
     *
     * 回包前的回调方法
     * 业务可以继承该方法，打印日志方便定位
     * @param string $xmlData 返回的xml参数
     *
     **/
    public function LogAfterProcess($xmlData)
    {

        Log::error("call back， return xml:" . $xmlData);
        return;
    }

    //重写回调处理函数

    /**
     * @param WxPayNotifyResults $data 回调解释出的参数
     * @param WxPayConfigInterface $config
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
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
            Log::error("签名验证".json_encode($e));
        }

        //TODO 3、处理业务逻辑
        Log::error("call back333:" . json_encode($data));
        $notfiyOutput = array();


        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            Log::error("订单查询失败...");
            return false;
        }
        return true;
    }
}


