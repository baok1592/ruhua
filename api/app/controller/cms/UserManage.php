<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/4 0004
 * Time: 8:41
 */

namespace app\controller\cms;


use app\model\User as UserModel;
use bases\BaseController;

class UserManage extends BaseController
{
    /**
     * 获取所有用户信息
     * @return mixed
     */
    public function getAllUser(){
       return UserModel::getAllUser();
    }
}