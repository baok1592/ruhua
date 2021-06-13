<?php
/**
 * 如花商城开源系统 -- 永久免费
 * =========================================================
 * 官方网址：http://www.phps.shop
 * QQ 交流群：728615087
 * Version：2.0
 */

namespace app\controller\auth;

use app\model\SysConfig as SysConfigModel;
use app\services\MergeService;
use bases\BaseCommon;
use exceptions\BaseException;
use exceptions\TokenException;
use think\Exception;
use app\model\User as UserModel;

//公众号Token
class Gzh extends Token
{
    protected $code;
    protected $gzhAppID;
    protected $gzhAppSecret;
    protected $wxExpire;
    protected $wxCodeUrl;
    //若提示“该链接无法访问”，请检查参数是否填写错误，是否拥有scope参数对应的授权作用域权限。
    //静默 scope=snsapi_base
    //手动 scope=snsapi_userinfo
    protected $access_token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s";

    protected $gzh_code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
    protected $gzh_jm_code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
    //公众号登陆，携带code换取openid
    protected $gzh_login_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';


    function __construct()
    {
        $where['key'] = ['gzh_appid', 'gzh_secret', 'wx_token_expire'];
        $data = SysConfigModel::where($where)->select();
        if (!$data || count($data) != 3) {
            throw new TokenException(['msg' => 'API中未填写配置信息']);
        }
        $arr = [];
        foreach ($data as $k => $v) {
            $arr[$v['key']] = $v['value'];
        }
        $this->gzhAppID = $arr['gzh_appid'];
        $this->gzhAppSecret = $arr['gzh_secret'];
        $this->wxExpire = $arr['wx_token_expire'];
    }

    //获取公众号的access_token，注意这里不是用户的access_token
    public function access_token()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
        $surl = sprintf($url, $this->gzhAppID, $this->gzhAppSecret);
        $access = (new BaseCommon())->curl_get($surl);
        $access = json_decode($access, true);
        $access_token = $access['access_token'];
        return $access_token;
    }

    public function get_ticket($access_token)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $access_token . '&type=jsapi';
        $json = (new BaseCommon())->curl_get($url);
        $arr = json_decode($json, true);
        $ticket = $arr['ticket'];
        return $ticket;
    }

    public function get_sign($ticket, $now_url)
    {
        $time = time();
        $jsapi_ticket = $ticket;
        $noncestr = 'Wmt8dfcjPz0z';
        $timestamp = $time;
        $url = $now_url;
        $str = "jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s";
        $sign = sprintf($str, $jsapi_ticket, $noncestr, $timestamp, $url);
        $sign = sha1($sign);
        $json['sign'] = $sign;
        $json['noncestr'] = $noncestr;
        $json['timestamp'] = $timestamp;
        $json['appid'] = $this->gzhAppID;
        return json($json);
    }


    //记得公众号平台设置：IP白名单 和 授权域名；ip是服务器IP，域名是前端域名
    public function getCodeUrl($url, $type)
    {
        //sprintf函数是把百分号（%）符号替换成一个作为参数进行传递的变量：%s=字符串,%u=正整数
        if ($type == 'userinfo') {
            $wxCodeUrl = sprintf($this->gzh_code_url, $this->gzhAppID, $url);
        } else {
            $wxCodeUrl = sprintf($this->gzh_jm_code_url, $this->gzhAppID, $url);
        }
        return $wxCodeUrl;
    }

    //获取token，openid
    public function getToken($code)
    {
        $wxLoginUrl = sprintf($this->gzh_login_url, $this->gzhAppID, $this->gzhAppSecret, $code);
        //注意code是临时的，所以向微信服务器提交只能使用一次
        $result = (new BaseCommon())->curl_get($wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            throw new TokenException(['msg' => '获取session_key及openID时异常，微信内部错误']);
        } else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->WxError($wxResult);
            } else {
                return $this->grantToken($wxResult);
            }
        }
    }

    //openid，uid放入缓存，$token做缓存键名;
    private function grantToken($wxResult)
    {
        $openid = $wxResult['openid'];
        $access_token = $wxResult['access_token'];
        $userinfo = $this->getUserInfo($openid, $access_token);
        $mergeMode = app('system')->getValue('merge_mode');
        $data = [];
        if (array_key_exists('unionid', $wxResult) && $mergeMode == 1) {
            $unionid = $wxResult['unionid'];
            $user = UserModel::where('unionid', $unionid)->find();
            if ($user) {
                $gzh_id = UserModel::where(['unionid' => $unionid, 'openid_gzh' => $openid])->find();
                if ($gzh_id) {
                    $uid = $gzh_id['id'];
                } else {
                    $user->save(['openid_gzh' => $openid]);
                    $uid = $user['id'];
                    (new MergeService())->mergeUser($uid, 'openid_gzh', $openid, 2);//合并
                }
            } else {
                $gzh_id = UserModel::where('openid_gzh', $openid)->find();
                if ($gzh_id) {
                    $gzh_id->save(['unionid' => $unionid]);
                    $uid = $gzh_id['id'];
                } else {
                    $data['openid_gzh'] = $openid;
                    $data['unionid'] = $unionid;
                    $data['nickname'] = base64_encode($userinfo['nickname']);
                    $data['headpic'] = $userinfo['headpic'];
                    if (array_key_exists('uniacid', $wxResult)) {
                        $data['uniacid'] = $wxResult['uniacid'];
                    }
                    $new_user = UserModel::create($data);
                    $uid = $new_user['id'];
                }
            }
        } else {
            $user = UserModel::where('openid_gzh', $openid)->find();
            if ($user) {
                $user->save(['nickname' => base64_encode($userinfo['nickname']),
                    'headpic' => $userinfo['headpic']]);
                $uid = $user['id'];
            } else {
                $data['openid_gzh'] = $openid;
                $data['nickname'] = base64_encode($userinfo['nickname']);
                $data['headpic'] = $userinfo['headpic'];
                if (array_key_exists('uniacid', $wxResult)) {
                    $data['uniacid'] = $wxResult['uniacid'];
                }
                $new_user = UserModel::create($data);
                $uid = $new_user['id'];
            }
        }
        if (!$uid) {
            throw new BaseException(['msg' => '用户注册失败']);
        }
        $cachedValue = $this->setWxCache($openid, $uid, $data);
        $token = $this->saveCache($cachedValue);
        return $token;
    }

    //组合uid，openid，权限
    private function setWxCache($openid, $uid, $data)
    {
        if (array_key_exists('uniacid', $data)) {
            $cache['uniacid'] = $data['uniacid'];
        }
        $cache['openid'] = $openid;
        $cache['uid'] = $uid;
        $cache['scope'] = 9;  // 推荐用枚举
        return $cache;
    }

    private function getUserInfo($openid, $access_token)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';
        $userinfo_url = sprintf($url, $access_token, $openid);
        $res = (new BaseCommon())->curl_get($userinfo_url);
        $userinfo = json_decode($res, true);

        if (!isset($userinfo['nickname'])) {
            return false;
        }
        $arr['nickname'] = $userinfo['nickname'];
        $arr['headpic'] = $userinfo['headimgurl'];
        return $arr;
    }


    //微信错误信息解析后抛出
    private function WxError($wxResult)
    {
        throw new TokenException(
            [
                'msg' => $wxResult['errmsg'],
                'errorCode' => $wxResult['errcode']
            ]);
    }


}