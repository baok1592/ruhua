<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 9:03
 */

namespace app\controller\user;


use app\model\User as UserModel;
use app\services\TokenService;
use bases\BaseController;

class User extends BaseController
{
    /**
     * 模拟用户登录获取TOKEN
     * @return mixed
     */
    public function userLogin()
    {
        return (new TokenService())->saveCache(['uid' => 15,'openid' => 'oq_jb4mLWx97WOEn7x38yM0YkFhs']);
    }
    
    /**
     * 获取用户基础信息
     */
    public function getInfo()
    {
        $uid = TokenService::getCurrentUid();
        $res=UserModel::with('vip')->field('id,nickname,headpic,mobile,invite_code as sfm',true)->find($uid);
        return app('json')->success($res);
    }

    /**
     * 获取发票信息
     * @return mixed
     */
    public function getCpy(){
        $uid = TokenService::getCurrentUid();
        $data= UserModel::getCpyInfo($uid);
        return app('json')->success($data);
    }

    /**
     * 修改
     * @return mixed
     */
    public function editCpy(){
        $uid = TokenService::getCurrentUid();
        $post=input('post.');
        $this->validate($post,['cpy_name'=>'require','cpy_num'=>'require','email'=>'require','user_name'=>'require']);
        return UserModel::editCpy($post,$uid);
    }

    /**
     * 获取小程序码
     * @return mixed
     */
    public function getXcxCode(){
        $uid=TokenService::getCurrentUid();
        $path=input('post.path');
        $scene=input('post.scene');
        return (new UserModel)->getXcxInviteUrl($uid,$path,$scene);
    }

    /**
     * 获取二维码
     * @return mixed
     */
    public function getWebCode(){
        $uid=TokenService::getCurrentUid();
        $path=input('post.path');
        $scene=input('post.scene');
        return (new UserModel)->getWebInviteUrl($uid,$path,$scene);
    }

}