<?php

namespace app\controller\auth;


use app\services\MergeService;
use exceptions\TokenException;
use app\model\User as UserModel;
use think\facade\Log;
use WxCode\demoWxCode;

class Auth
{
    /********************  公众号  ************************/
    //请求公众号code
    public function wxcodeUrl($url, $type = '')
    {
        $gzh_service = new Gzh();
        $res = $gzh_service->getCodeUrl($url, $type);
        return $res;
    }

    public function getToken($code)
    {
        $usertoken = new Gzh;
        $token = $usertoken->getToken($code);
        $data = ['token' => $token];
        return json($data);
    }


    public function getSign($url)
    {
        $ucid = TokenService::getCurrentTokenVar('ucid');
        $gzh_service = new GzhTokenService($ucid);
        $ticket = Cache::get('ticket');
        if (!$ticket) {
            $access_token = $gzh_service->access_token();
            $ticket = $gzh_service->get_ticket($access_token);
            $res = Cache::set('ticket', $ticket, 7200);
            if (!$res) {
                throw new BaseException(['msg' => 'ticket存储失败']);
            }
        }
        $sign_all = $gzh_service->get_sign($ticket, $url);
        return $sign_all;
    }

    /********************  小程序 ↓  ************************/
    /*
        * 用途：将“openid，uid，权限”存入缓存value，生成一个token做缓存的key并返回
        * 1、获取code
        * 2、组合code,Appid与Secret生成URL，
        * 3、curl方式向微信服务器提交，获取openid;注意一个code只能使用一次
        * 4、判断openid，数据库不存在则写入；从数据库获取该openid的用户UID
        * 5、生成token，token是一个随机字符串，它是缓存的key；将“openid，uid，权限”存入缓存value
        * 6、返回token
        */
    public function getXcxToken($code)
    {
        $usertoken = new Xcx($code);
        $token = $usertoken->getToken();
        $data = ['token' => $token];
        return json($data);
    }

    public function xcx_upinfo($nickname, $headpic, $keys, $iv)
    {
        $uid = Token::getCurrentUid();
        $session_key = Token::getCurrentTokenVar('session_key');
        $nickname = base64_encode($nickname);
        $user = UserModel::where('id', $uid)->find();
        $mergeMode = app('system')->getValue('merge_mode');
        if (!$user['unionid'] && $mergeMode == 1) {
            $unionid = (new demoWxCode())->getUnionId($keys, $iv, $session_key);
            if ($unionid < 0) {
            } else {
                $data['unionid'] = $unionid;
            }

        }
        $data['nickname'] = $nickname;
        $data['headpic'] = $headpic;
        $user->save($data);
    }

    /********************  微信 + 小程序 共用  ************************/
    //验证token，返回false,true
    public function verifyToken($token)
    {
        if (!$token) {
            throw new TokenException(['msg' => 'token不允许为空']);
        }
        $valid = Token::verifyToken($token);
        $arr = ['isValid' => $valid];
        return json($arr);
    }

    /********************  app  ************************/
    public function getAppToken($authResult)
    {
        $usertoken = new AppToken();
        $token = $usertoken->getToken($authResult);
        $data = ['token' => $token];
        return json($data);
    }

    /**
     * 合并测试
     */
    public function testGetToken()
    {
        return (new MergeService())->mergeUser(15, 'openid', 'oq_jb4mLWx97WOEn7x38yM0YkFhs', 2);
    }

}