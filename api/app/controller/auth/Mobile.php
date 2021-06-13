<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/14 0014
 * Time: 16:51
 */

namespace app\controller\auth;


use Aliyun\api_demo\SmsDemo;
use app\model\User as UserModel;
use bases\BaseController;

class Mobile extends BaseController
{
    /**
     * 手机登录获取验证码
     * return mixed
     */
    public function getLoginCode()
    {
        if (app('system')->getValue('merge_mode') == 3) {
            $post = input('post.');
            $this->validate($post, ['mobile' => 'require|length:11']);
            $mobile_user = UserModel::where('mobile', $post['mobile'])->find();
            if ($mobile_user) {
                $data['code'] = rand(10000, 999999);
                $data['code_time'] = time() + (60 * 5);
                $mobile_user->save($data);
            } else {
                $data['mobile'] = $post['mobile'];
                $data['code'] = rand(10000, 999999);
                $data['code_time'] = time() + (60 * 5);
                UserModel::create($data);
            }
            $send_res=SmsDemo::sendSms($post['mobile'], $data['code']);
            return app('json')->success();
        } else {
            return app('json')->fail('未开启手机登录');
        }
    }

    /**
     * 手机登录获取token
     * @return string
     */
    public function getMobileToken()
    {
        if (app('system')->getValue('merge_mode') == 3) {
            $post = input('post.');
            $this->validate($post, ['mobile' => 'require|length:11','code'=>'require']);
            //$res = UserModel::where(['mobile' =>$post['mobile'], 'code' => $post['code']])->whereTime('code_time', '>', time())->find();
            $res = UserModel::where(['mobile' =>$post['mobile'], 'code' => $post['code']])->find(); //用于测试
            if (!$res) {
                return app('json')->fail('验证码错误');
            }
            $cache['uid'] = $res['id'];
            $cache['scope'] = 9;  // 推荐用枚举
            $token = (new Token())->saveCache($cache);
            return app('json')->success($token);
        } else {
            return app('json')->fail('未开启手机登录');
        }
    }

}