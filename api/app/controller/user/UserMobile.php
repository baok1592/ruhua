<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/14 0014
 * Time: 9:16
 */

namespace app\controller\user;

use app\model\User as UserModel;
use app\services\TokenService;
use bases\BaseController;

class UserMobile extends BaseController
{
    /**
     * 微信授权绑定手机号
     * @return mixed
     */
    public function getWxMobile(){
        $post=input('post.');
        $this->validate($post,['encryptedData'=>'require','iv'=>'require']);
        $uid=TokenService::getCurrentUid();
        return UserModel::addWxMobile($uid,$post);
    }

    /**
     * 手机绑定获取验证码
     * @return mixed
     */
    public function getMobileCode(){
        if(app('system')->getValue('merge_mode')==2){
            $post=input('post.');
            $this->validate($post,['mobile'=>'require|length:11']);
            $uid=TokenService::getCurrentUid();
            return UserModel::addMobile($uid,$post['mobile']);
        }else{
            return app('json')->fail('未开启手机号关联模式');
        }
    }

    public function addMobileBind(){
        if(app('system')->getValue('merge_mode')==2){
            $post=input('post.');
            $this->validate($post,['type'=>'require','code'=>'require','mobile'=>'require|length:11']);
            $uid=TokenService::getCurrentUid();
            return UserModel::bindMobile($post['type'],$uid,$post['mobile'],$post['code']);
        }else{
            return app('json')->fail('未开启手机号关联模式');
        }
    }

}