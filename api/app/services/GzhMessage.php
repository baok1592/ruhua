<?php


namespace app\services;


use bases\BaseCommon;
use think\Exception;
use think\facade\Log;


//公众号模板消息
class GzhMessage
{
    private $sendUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=%s";
    private $touser;
    private $color = 'black'; //不让子类控制颜色

    protected $tplID;
    protected $page;
    protected $formID;
    protected $data;
    protected $emphasisKeyWord;

    protected function AccessToken()
    {
        $accessToken = new AccessToken();
        $token = $accessToken->get();
        $this->sendUrl = sprintf($this->sendUrl, $token['access_token']);//把百分号（%）符号替换成一个作为参数进行传递的变量：
    }

    // 公众号发送模板消息
    protected function sendMessage($openID)
    {
        $data = [
            'touser' => $openID,
            'template_id' => $this->tplID,
            'url'=>"",
            'data' => $this->data,
        ];
        //Log::error('url:'.$this->sendUrl);
        //Log::error('sendMessage:'.json_encode($data,JSON_UNESCAPED_UNICODE));
        $result = (new BaseCommon())->curl_post($this->sendUrl, $data);
        $result = json_decode($result, true);

        if ($result['errcode'] == 0) {
            return true;
        } else {
            Log::error(json_encode($result));
            throw new Exception('服务通知发送失败,  ' . $result['errmsg']);
        }
    }
}