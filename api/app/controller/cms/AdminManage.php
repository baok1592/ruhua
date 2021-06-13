<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14 0014
 * Time: 14:19
 */

namespace app\controller\cms;


use app\model\User as UserModel;
use app\services\AdminService;
use bases\BaseController;

class AdminManage extends BaseController
{
    /**
     *更新某管理员
     **/
    public function editAdmin()
    {
        $rule =[
            'id' => 'require|number',
            'gid' => 'require|number',
        ];
        $form = input('param.');
        $this->validate($form,$rule);
        return AdminService::getInstance()->editAdmin($form);
    }

    /**
     * 增加管理员
     * @return mixed
     */
    public function addAdmin()
    {
        $rule = [
            'username'=> 'require|alphaDash|max:30',
            'password'=> 'require|alphaDash|min:6',
            'gid'=> 'require'
        ];
        $post=input('post.');
        $this->validate($post,$rule);
        return AdminService::getInstance()->register($post);
    }

    /**
     * 获取所有管理员
     * @return mixed
     */
    public function getAdminAll()
    {
        return AdminService::getInstance()->getAllAdmin();
    }

    /**
     * 删除管理员
     * @param $id
     * @return int
     */
    public function deleteAdmin($id)
    {
      return AdminService::getInstance()->deleteAdmin($id);
    }

    /**
     * 设置前端管理员权限
     * @param $id
     * @param $auth_id
     * @return int
     */
    public function setWebAuth($id='', $auth_id='')
    {
        $rule = [
            'id'=> 'require',
            'auth_id'=> 'require',
        ];
        $post=input('post.');
        $this->validate($post,$rule);
        $res = UserModel::where('id', $id)->update(['web_auth_id' => $auth_id]);
       if($res){
           return app('json')->success();
       }else{
           return app('json')->success();
       }
    }
}