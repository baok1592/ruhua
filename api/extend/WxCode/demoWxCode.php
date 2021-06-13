<?php

namespace WxCode;

class  demoWxCode
{
    public function getUnionId($encryptedData, $iv, $sessionKey)
    {
        $appid = app('system')->getValue('wx_app_id');

        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $res = $pc->decryptData($encryptedData, $iv, $data);
        if ($res['ErrorCode'] == 0) {
            $data=json_decode($res['data'],true);
            return $data['unionId'];
        } else {
            return $res['ErrorCode'];
        }
    }

    public function decryptData($encryptedData, $iv, $sessionKey)
    {
        $appid = app('system')->getValue('wx_app_id');

        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $res = $pc->decryptData($encryptedData, $iv, $data);
        if ($res['ErrorCode'] == 0) {
            $data=json_decode($res['data'],true);
            return $data;
        } else {
            return $res['ErrorCode'];
        }
    }
}







