<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/23
 */
namespace services;

class HttpService
{
    //错误信息
    private static $curlError;
    //header头信息
    private static $headerStr;
    //请求状态
    private static $status;

    /**
     * @return string
     */
    public static function getCurlError()
    {
        return self::$curlError;
    }


    public static function getStatus()
    {
        return self::$status;
    }

    public static function getRequest($url, $data = array(), $header = false, $timeout = 10)
    {
        if (!empty($data)) {
            $url .= (stripos($url, '?') === false ? '?' : '&');
            $url .= (is_array($data) ? http_build_query($data) : $data);
        }

        return self::request($url, 'get', array(), $header, $timeout);
    }

    public static function request($url, $method = 'get', $data = array(), $header = false, $timeout = 15)
    {
        self::$status = null;
        self::$curlError = null;
        self::$headerStr = null;

        $curl = curl_init($url);
        $method = strtoupper($method);
        //请求方式
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        //post请求
        if ($method == 'POST') curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //超时时间
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        //设置header头
        if ($header !== false) curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        //返回抓取数据
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //输出header头信息
        curl_setopt($curl, CURLOPT_HEADER, true);
        //TRUE 时追踪句柄的请求字符串，从 PHP 5.1.3 开始可用。这个很关键，就是允许你查看请求header
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        //https请求
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        self::$curlError = curl_error($curl);

        list($content, $status) = [curl_exec($curl), curl_getinfo($curl), curl_close($curl)];
        self::$status = $status;
        self::$headerStr = trim(substr($content, 0, $status['header_size']));
        $content = trim(substr($content, $status['header_size']));
        return (intval($status["http_code"]) === 200) ? $content : false;
    }

    public static function postRequest($url, array $data = array(), $header = false, $timeout = 10)
    {
        return self::request($url, 'post', $data, $header, $timeout);
    }

    public static function getHeaderStr()
    {
        return self::$headerStr;
    }

    public static function getHeader()
    {
        $headArr = explode("\r\n", self::$headerStr);
        return $headArr;
    }

}