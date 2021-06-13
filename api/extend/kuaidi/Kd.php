<?php

namespace kuaidi;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/8 0008
 * Time: 15:50
 */
class Kd
{
    protected $host = "https://kdwlcxf.market.alicloudapi.com";//api访问链接
    public static $url = "https://1274406536131832.cn-hangzhou.fc.aliyuncs.com/2016-08-15/proxy/qy/http_test/?a=123";//aliyun访问链接
    protected $path = "/kdwlcx";//API访问后缀
    protected $method = "GET";
    protected $appcode = "";//替换成自己的阿里云appcode
    protected $courier = "";//快递公司
    protected $courier_num = "";//单号

    public function __construct($code, $courier, $courier_num)
    {
        $this->appcode = $code;
        $this->courier = $courier;
        $this->courier_num = $courier_num;
    }

    public function get()
    {

        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $this->appcode);
        $querys = "no=" . $this->courier_num . "&type=" . $this->courier;  //参数写在这里
        $url = $this->host . $this->path . "?" . $querys;//url拼接
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $this->host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $res = curl_exec($curl);
        return $res;
    }
}