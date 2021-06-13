<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/25 0025
 * Time: 10:18
 */

namespace app\controller\auth;


use app\model\User as UserModel;
use app\services\MergeService;
use exceptions\BaseException;

class AppToken extends Token
{
    //openid，uid放入缓存，$token做缓存键名;
    public function getToken($authResult)
    {
        $openid = $authResult['openid'];
        $unionid = $authResult['unionid'];
        $mergeMode = app('system')->getValue('merge_mode');
        $data=[];
        if ($mergeMode == 1) {
            $user = UserModel::where('unionid', $unionid)->find();
            if ($user) {
                $app_id = UserModel::where(['unionid' => $unionid, 'openid_app' => $openid])->find();
                if ($app_id) {
                    $uid = $app_id['id'];
                } else {
                    $user->save(['openid_app' => $openid]);
                    $uid = $user['id'];
                    (new MergeService())->mergeUser($uid, 'openid_app', $openid, 2);//合并
                }
            } else {
                $app_id = UserModel::where('openid_app', $openid)->find();
                if ($app_id) {
                    $app_id->save(['unionid' => $unionid]);
                    $uid = $app_id['id'];
                } else {
                    $data['openid_app']=$openid;
                    $data['unionid']=$unionid;
                    if (array_key_exists('uniacid', $authResult)) {
                        $data['uniacid'] = $authResult['uniacid'];
                    }
                    $new_user = UserModel::create($data);
                    $uid = $new_user['id'];
                }
            }
        } else {
            $app_id = UserModel::where('openid_app', $openid)->find();
            if ($app_id) {
                $uid = $app_id['id'];
            } else {
                $data['openid_app']=$openid;
                if (array_key_exists('uniacid', $authResult)) {
                    $data['uniacid'] = $authResult['uniacid'];
                }
                $new_user = UserModel::create($data);
                $uid = $new_user['id'];
            }
        }
        if (!$uid) {
            throw new BaseException(['msg' => '用户注册失败']);
        }
        $cachedValue = $this->setWxCache($openid, $uid,$data);
        $token = $this->saveCache($cachedValue);
        return $token;
    }

    //组合uid，openid，权限
    private function setWxCache($openid, $uid,$data)
    {
        if (array_key_exists('uniacid', $data)) {
            $cache['uniacid'] = $data['uniacid'];
        }
        $cache['openid'] = $openid;
        $cache['uid'] = $uid;
        $cache['scope'] = 9;  // 推荐用枚举
        return $cache;
    }
}