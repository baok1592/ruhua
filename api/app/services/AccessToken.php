<?php

namespace app\services;
use app\model\SysConfig;javascript:;
use bases\BaseCommon;
use exceptions\BaseException;
use think\Exception;

class AccessToken
{
    private $tokenUrl;
    private $xcxTokenUrl;
    const TOKEN_CACHED_KEY = 'access';
    const TOKEN_EXPIRE_IN = 7000;

    function __construct()
    {

        $arr=SysConfig::where(['type'=>2])->select();
        $cg=[];
        foreach ($arr as $k=>$v){
            if(in_array($v['key'],['gzh_appid','gzh_secret','wx_app_id','wx_app_secret'])){
                $cg[$v['key']]=$v['value'];
            }
        }
        if(empty($cg['gzh_appid']) && empty($cg['wx_appid'])) {
            throw new BaseException(['msg'=>'缺少appid']);
        }
        if(empty($cg['gzh_secret']) && empty($cg['wx_app_secret'])) {
            throw new BaseException(['msg'=>'缺少secret']);
        }
        $appid=$cg['gzh_appid']?$cg['gzh_appid']:$cg['wx_app_id'];
        $secret=$cg['gzh_secret']?$cg['gzh_secret']:$cg['wx_app_secret'];
       
        $access_token_url = config('setting.access_token_url');
        $url = sprintf($access_token_url, $appid,$secret);
        $this->tokenUrl = $url;
        $xcx_url=sprintf($access_token_url, $cg['wx_app_id'],$cg['wx_app_secret']);
        $this->xcxTokenUrl = $xcx_url;
    }

    // 建议用户规模小时每次直接去微信服务器取最新的token
    // 但微信access_token接口获取是有限制的 2000次/天
    //公众号获取access_token
    public function get()
    {
        $token = $this->getFromCache();
        if (!$token)
        {
            return $this->getFromWxServer(1);//
        }else {
            return $token;
        }
    }
    //小程序获取access_token
    public function getXcx()
    {
        $token = $this->getFromCache();
        if (!$token)
        {
            return $this->getFromWxServer(2);
        }else {
            return $token;
        }
    }

    private function getFromCache()
    {
        $token = cache(self::TOKEN_CACHED_KEY);
        if ($token)
        {
            return $token;
        }
        return null;
    }

    //type:1公众号，2小程序。
    private function getFromWxServer($type)
    {
        if($type==1){
            $token = (new BaseCommon())->curl_get($this->tokenUrl);
        }else if($type==2){
            $token = (new BaseCommon())->curl_get($this->xcxTokenUrl);
        }
        $token = json_decode($token, true);
        if (!$token)
        {
            throw new BaseException('获取AccessToken异常');
        }
        if (!empty($token['errcode']))
        {
            throw new BaseException(['msg'=>$token]);
        }
        $this->saveToCache($token);
        //return $token['access_token'];
      	return $token;
    }

    private function saveToCache($token){
        cache(self::TOKEN_CACHED_KEY, $token, self::TOKEN_EXPIRE_IN);
    }
}