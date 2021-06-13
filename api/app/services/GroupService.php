<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/26 0026
 * Time: 11:46
 */

namespace app\services;

use app\model\Admin as AdminModel;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Request;

/**
 * 权限管理service
 * Class GroupService
 * @package app\services
 */
class GroupService
{
    /**
     * 检查是否有权限
     * @param $id
     * @return bool
     */
    public function checkRule($id)
    {
        $rule = $this->getGroupRule();//获取缓存规则数据
        $rule = explode(',', $rule);
        $admin = AdminModel::with(['group'])->where('id', $id)->find();

        if (!$admin) {
            return false;
        }
        $con = Request::controller();
        $con = str_ireplace('cms.', '', $con);
        $action = Request::action();
        $action = $con . '/' . $action;
        if (!in_array($con, $rule) && !in_array($action, $rule)) {
            return true;
        }
        $admin_rule = explode(',', $admin['group']['rule']);
        if (!$admin_rule[0]) {
            return false;
        }
        foreach ($admin_rule as $k => $v) {
            $name = Db::name('group_rule')->where('id', $v)->value('name');
            if ($v == 1) {
                $name = explode(',', $name);
                if (in_array($con, $name)) {
                    return true;
                }
            }
            if ($con == $name) {
                return true;
            }
            if ($action == $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取权限规则
     * @return mixed|string
     */
    private function getGroupRule()
    {
        $res = Cache::get('GroupRule');
        if (!$res) {
            $str = '';
            $rule = Db::name('group_rule')->select();
            foreach ($rule as $k => $v) {
                if ($v['id'] == 1) {
                    $str = $v['name'];
                } else {
                    $str = $str . ',' . $v['name'];
                }
            }
            Cache::set('GroupRule', $str);
            $res = $str;
        }
        return $res;
    }

}