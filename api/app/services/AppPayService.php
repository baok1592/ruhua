<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/3 0003
 * Time: 13:24
 */

namespace app\services;


use app\model\Order as OrderModel;
use app\model\SysConfig;
use enum\OrderEnum;
use exceptions\BaseException;
use think\Exception;

class AppPayService
{
    private $orderID;
    private $orderNO;
    /**
     * 配置参数
     */
    private $config = array(
        'appid' => '',//"wxcf1dded808489e2c",    /*微信开放平台上的应用id*/
        'mch_id' => "",//"1440493402",   /*微信申请成功之后邮件中的商户id*/
        'api_key' => ""    /*在微信商户平台上自己设定的api密钥 32位*/
    );


    function __construct($orderID)
    {
        $appid = app('system')->getValue('app_appid');
        $mch_id = app('system')->getValue('pay_num');
        $api_key = app('system')->getValue('pay_key');
        if (!$orderID) {
            throw new Exception('订单号不允许为NULL');
        }
        $this->config['appid'] = $appid;
        $this->config['mch_id'] = $mch_id;
        $this->config['api_key'] = $api_key;
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
        event('CheckStock', $this->orderID);

        //检测是否未拼团订单，并检测是否能参与拼团
        event('CheckItem', $this->orderID);

        $order_money = OrderModel::getOrderAttr($this->orderID, 'order_money');//获取订单指定字段
        $order_money = 100 * $order_money;
        return $this->getPrePayOrder($order_money);//进行微信支付
    }


    //获取预支付订单
    public function getPrePayOrder($order_money)
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $api_url = SysConfig::where(['key' => 'api_url'])->value('value');
        $onoce_str = $this->getRandChar(32);

        $data["appid"] = $this->config["appid"];
        $data["body"] = '商城';
        $data["mch_id"] = $this->config['mch_id'];
        $data["nonce_str"] = $onoce_str;
        $data["notify_url"] = $api_url . "/order/pay/notify";
        $data["out_trade_no"] = $this->orderNO;
        $data["spbill_create_ip"] = $this->get_client_ip();
        $data["total_fee"] = $order_money;
        $data["trade_type"] = "APP";
        $s = $this->getSign($data, false);
        $data["sign"] = $s;

        $xml = $this->arrayToXml($data);// 数组转成xml
        $response = $this->postXmlCurl($xml, $url);//发起请求
        $response = $this->xmlToArray($response);//将微信返回的结果xml转成数组
        if ($response['result_code'] == 'SUCCESS') {
            $this->recordPreOrder($response['prepay_id']);//写入prepay_id
            $res = $this->getOrder($response['prepay_id']);
            return json($res);
        } else {
           return app('json')->fail($response['err_code_des']);
        }
    }

    //执行第二次签名，才能返回给客户端使用
    public function getOrder($prepayId)
    {

        $data["appid"] = $this->config["appid"];
        $data["noncestr"] = $this->getRandChar(32);;
        $data["package"] = "Sign=WXPay";
        $data["partnerid"] = $this->config['mch_id'];
        $data["prepayid"] = $prepayId;
        $data["timestamp"] = time();
        $s = $this->getSign($data, false);
        $data["sign"] = $s;

        return $data;
    }

    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('order_id', $this->orderID)
            ->update(['prepay_id' => $wxOrder]);
    }

    /*
        生成签名
    */
    public function getSign($Obj)
    {
        foreach ($Obj as $k => $v) {
            $Parameters[strtolower($k)] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo "【string】 =".$String."</br>";
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $this->config['api_key'];
//        echo "<textarea style='width: 50%; height: 150px;'>$String</textarea> <br />";
        //签名步骤三：MD5加密
        $result_ = strtoupper(md5($String));
        return $result_;
    }

    //获取指定长度的随机字符串
    public function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    //数组转xml
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";

            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    //post https请求，CURLOPT_POSTFIELDS xml格式
    public function postXmlCurl($xml, $url, $second = 30)
    {
        //初始化curl
        $ch = curl_init();
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error" . "<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }

    /*
        获取当前服务器的IP
    */
    public function get_client_ip()
    {
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $cip = getenv("HTTP_CLIENT_IP");
        } else {
            $cip = "unknown";
        }
        return $cip;
    }

    //将数组转成uri字符串
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= strtolower($k) . "=" . $v . "&";
        }
        $reqPar = [];
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    /**
     *将xml转为array
     * @param $xml
     * @return mixed
     */
    public function xmlToArray($xml)
    {
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }


    /**
     * xml转成数组
     */
//    function xmlstr_to_array($xmlstr) {
//        $doc = new DOMDocument();
//        $doc->loadXML($xmlstr);
//        return $this->domnode_to_array($doc->documentElement);
//    }
    function domnode_to_array($node)
    {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = $this->domnode_to_array($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;
                        if (!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    } elseif ($v) {
                        $output = (string)$v;
                    }
                }
                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $a = array();
                        foreach ($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string)$attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) == 1 && $t != '@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }

    private function checkOrderValid()
    {
        $order = OrderModel::where('order_id', $this->orderID)->where('state',0)->find();
        if (!$order) {
            throw new BaseException(['msg' => '订单不存在']);
        }
        if (!TokenService::isValidOperate($order->user_id)) {
            throw new BaseException(['msg' => '非法操作']);
        }
        //非待支付状态，则终止
        if ($order->payment_state != OrderEnum::UNPAYID) {
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