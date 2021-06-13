<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14 0014
 * Time: 14:38
 */

namespace app\services;


use app\model\Admin as AdminModel;
use bases\BaseCommon;
use enum\ScopeEnum;
use think\facade\Db;

class AdminService extends TokenService
{
    /**
     * @var 实例句柄
     */
    private static $instance;

    /**
     * 获取实例句柄
     * @return AdminService
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 注册管理员
     * @param $post
     * @return mixed
     */
    public function register($post)
    {
        $user = AdminModel::where('username', $post['username'])->find();
        if ($user) {
            return app('json')->fail('用户名已存在');
        }
        $data['username'] = $post['username'];
        $data['password'] = (new BaseCommon())->password($post['password']);
        $data['group_id'] = $post['gid'];
        if (array_key_exists('description', $post)) {
            $data['description'] = $post['description'];
        }
        $res = AdminModel::create($data);
        if ($res) {
            return app('json')->success($res['id']);
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 登陆，并返回token
     * @param $user
     * @param $pwd
     * @return \think\response\Json
     */
    public function loginService($user, $pwd)
    {
        // $password = password($pwd);    //common文件的函数
        $password = $pwd;
        $where['username'] = $user;
        $where['password'] = (new BaseCommon())->password($password);
        $user = Db::name('Admin')->where($where)->find();
        if (!$user) {
            return app('json')->fail('账号或密码错误');
        }
        if ($user['state'] == 1) {
            return app('json')->fail('已禁用');
        }
        $cachedValue = $this->setWxCache($user);//仅组合
        $res['token'] = $this->saveCache($cachedValue);
        return app('json')->success($res);
//        return json($res);
    }

    /**
     * 组合uid，username，权限
     * @param $user
     * @return mixed
     */
    private function setWxCache($user)
    {
        if (array_key_exists('uniacid', $user)) {
            $cache['uniacid'] = $user['uniacid'];
        }
        $cache['admin_id'] = $user['id'];
        $cache['username'] = $user['username'];
        $cache['scope'] = ScopeEnum::Root;  // scope=16 代表App用户的权限数值
        return $cache;
    }

    /**
     * 修改密码
     * @param $form
     * @return mixed
     */
    public function editAdminPwd($form)
    {
        $aid = TokenService::getCurrentAid();
        $admin = AdminModel::find($aid);
        if (!$admin) {
            return app('json')->fail('用户信息错误');
        }
        if ($admin->password != (new BaseCommon())->password($form['old_psw'])) {
            return app('json')->fail('原密码错误');
        }
        $admin->password = (new BaseCommon())->password($form['new_psw']);
        $res = $admin->save();
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 修改管理员信息
     * @param $form
     * @return mixed
     */
    public function editAdmin($form)
    {
        $id = $form['id'];
        $data['group_id'] = $form['gid'];
        if (array_key_exists('description', $form)) {
            $data['description'] = $form['description'];
        }
        $res = AdminModel::where('id', $id)->update($data);
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
//        return $res ? 1 : 0;
    }

    /**
     * 获取所有管理员
     * @return \think\Collection
     */
    public function getAllAdmin()
    {
        $data=AdminModel::select();
         return app('json')->success($data);
    }

    /**
     * 删除管理员
     * @param $id
     * @return mixed
     */
    public function deleteAdmin($id){
        if ($id <= 1) {
            return app('json')->auth_err('不能删除最高管理员');
        }
        $res=AdminModel::where('id',$id)->delete();
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
//        return $res?1:0;
    }
}