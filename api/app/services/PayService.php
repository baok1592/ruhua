<?php
 

namespace app\services;


use app\model\Order as OrderModel;
use app\model\SysConfig;
use enum\OrderEnum;
use exceptions\BaseException;
use think\Exception;
use WxPay\WxPayApi;
use WxPay\WxPayData;
use WxPay\WxPayJsApiPay;
use WxPay\WxPayUnifiedOrder;


class PayService
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if (!$orderID)
        {
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    //验证订单、支付、改变状态
    public function pay()
    {
        //订单号可能根本就不存在
        //订单号确实是存在的，但是，订单号和当前用户是不匹配的
        //订单有可能已经被支付过
        //将购买的商品数量赋给类属性
         $this->checkOrderValid();

        //进行库存量检测
        event('CheckStock',$this->orderID);

        //检测是否未拼团订单，并检测是否能参与拼团
        event('CheckItem',$this->orderID);

        //不支付，自动更新为已支付状态；  这是自定义的假数据
        /*$data['out_trade_no'] = OrderModel::getOrderAttr($this->orderID,'order_num');
        $data['result_code'] = 'SUCCESS';
        $result = (new WxNotifyService) -> NotifyProcess($data);*/

        $order_money = OrderModel::getOrderAttr($this->orderID,'order_money');//获取订单指定字段
        $order_money=100*$order_money;
        $result = $this->makeWxPreOrder($order_money);//进行微信支付
        return json($result);
    }

    //进行支付
    private function makeWxPreOrder($totalPrice)
    {
        $api_url=SysConfig::where(['key'=>'api_url'])->value('value');
        $openid = TokenService::getCurrentTokenVar('openid');
        if (!$openid)
        {
            throw new BaseException();
        }
        new WxPayData();
        $wxOrderData = new WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice);
        $wxOrderData->SetBody('商城');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url($api_url."/order/pay/notify");
        $res = $this->getPaySignature($wxOrderData);

        return $res;
    }

    //获取预支付订单
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = WxPayApi::unifiedOrder($wxOrderData);    //获取预支付id:prepay_id
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            \think\facade\Log::record($wxOrder, 'error');
            \think\facade\Log::record('获取预支付订单失败', 'error');
            throw new BaseException(['msg'=>$wxOrder['err_code_des']]);
        }
        //prepay_id
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    //
    private function sign($wxOrder)
    {
        $jsApiPayData = new WxPayJsApiPay();
        $app_id = SysConfig::where('key','wx_app_id')->value('value');
        $jsApiPayData->SetAppid($app_id);
        $jsApiPayData->SetTimeStamp((string)time());

        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);

        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;

        unset($rawValues['appId']);

        return $rawValues;
    }

    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('order_id', $this->orderID)
            ->update(['prepay_id' =>$wxOrder['prepay_id']]);
    }

    private function checkOrderValid()
    {
        $order = OrderModel::where('order_id', $this->orderID)->where('state',0)->find();
        if (!$order)
        {
            throw new BaseException(['msg' => '订单不存在或已过期']);
        }
        if (!TokenService::isValidOperate($order->user_id))
        {
            throw new BaseException(['msg' => '非法操作']);
        }
        //非待支付状态，则终止
        if ($order->payment_state != OrderEnum::UNPAYID)
        {
            throw new BaseException(
                [
                    'msg' => '订单已支付过啦',
                    'errorCode' => 60003,
                    'code' => 400
                ]);
        }
        $this->orderNO = $order->order_num;
        return true;
    }
}