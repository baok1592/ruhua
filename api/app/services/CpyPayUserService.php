<?php

namespace app\services;


use exceptions\BaseException;
use think\facade\Log;
use think\facade\Request;


class CpyPayUserService
{
    const API_URL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    private $_appid;
    private $_merchantid;
    private $_key;
    private $apiclient_cert;
    private $apiclient_key;

    public function __construct()
    {
        //固定使用获取小程序appid，如需使用公众号自动打款，需修改为公众号appid，并确保传入的用户openid为公众号下的openid
        $appId=app('system')->getValue('wx_app_id');
        if(!$appId){
            exit;
        }
        $this->_appid =$appId;
        $this->_merchantid =app('system')->getValue('pay_num');
        $this->_key = app('system')->getValue('pay_key');
    }

    //发起提现
    public function transfer($data)
    {
        //支付信息
        $webdata = array(
            'mch_appid' => $this->_appid,//商户账号appid
            'mchid' => $this->_merchantid,//商户号
            'nonce_str' => $this->getNonceStr(),//随机字符串
            'partner_trade_no' => $data['order_sn'], //商户订单号，需要唯一
            'openid' => $data['openid'],//转账用户的openid
            're_user_name' => $data['truename'],
            'check_name' => 'NO_CHECK', //OPTION_CHECK不强制校验真实姓名, FORCE_CHECK：强制 NO_CHECK：
            'amount' => $data['money'] * 100, //付款金额单位为分
            'desc' => $data['desc'],//企业付款描述信息
            'spbill_create_ip' => Request::ip(),//获取IP
        );
        foreach ($webdata as $k => $v) {
            $tarr[] = $k . '=' . $v;
        }
        sort($tarr);
        $sign = implode($tarr, '&');
        $sign .= '&key=' . $this->_key;
        $webdata['sign'] = strtoupper(md5($sign));
        $wget = $this->ArrToXml($webdata);//数组转XML
        // $res = $this->curl_post_ssl(self::API_URL, $wget);//发送数据
        $res = $this->postXmlCurl(self::API_URL, $wget, true);//发送数据

        if (!$res) {
            return array('code' => 0, 'msg' => "不能连接到服务器");
        }
        $content = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (strval($content->return_code) == 'FAIL') {
            Log::write(strval($content->return_msg),'error');
            return array('code' => 0, 'msg' => strval($content->return_msg));
        }
        if (strval($content->result_code) == 'FAIL') {
            Log::write(strval($content->err_code_des),'error');
            return array('code' => 0, 'msg' => strval($content->err_code_des));
        }
        $rdata = array(
            'mch_appid' => strval($content->mch_appid),
            'mchid' => strval($content->mchid),
            'device_info' => strval($content->device_info),
            'nonce_str' => strval($content->nonce_str),
            'result_code' => strval($content->result_code),
            'partner_trade_no' => strval($content->partner_trade_no),
            'payment_no' => strval($content->payment_no),
            'payment_time' => strval($content->payment_time),
        );
        return array('code' => 1, 'data' => $rdata);
    }

    private function postXmlCurl($url, $xml, $useCert = false, $second = 30)
    {

//        dump($xml);
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if ($useCert == true) {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT,  dirname(__FILE__) .'/../../extend/WxPay/cert/apiclient_cert.pem');
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY,  dirname(__FILE__) .'/../../extend/WxPay/cert/apiclient_key.pem');
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);

        dump($data);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new BaseException(['msg'=>"curl出错，错误码:$error"]);
        }
    }

    //数组转XML
    protected function ArrToXml($arr)
    {
        if (!is_array($arr) || count($arr) == 0) return '';
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    //随机字符串(不长于32位)
    protected function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }


}