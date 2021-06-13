<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 15:50
 */

namespace app\controller\cms;


use app\model\Group as GroupModel;
use app\validate\IDPostiveInt;
use bases\BaseController;
use think\facade\Db;

class Group extends BaseController
{
    /**
     * 添加分组
     * @return int
     */
    public function addGroup()
    {
        $rule = [
            'name' => 'require|max:255',
            'rule_ids' => 'require'
        ];
        $post = input('post.');
        $this->validate($post, $rule);
        return GroupModel::addGroup($post);
    }

    /**
     * 修改分组
     * @return int
     */
    public function editGroup()
    {
        $rule = [
            'id' => 'require|number',
            'name' => 'require|max:255',
            'rule_ids' => 'require',
        ];
        $post = input('post.');
        $this->validate($post, $rule);
        if ($post['id'] == 1) {
            return app('json')->fail('超级管理组不能');
        }
        return GroupModel::editGroup($post);
    }

    /**
     * 删除分组
     * @param $id
     * @return int
     */
    public function deleteGroup($id)
    {
        (new IDPostiveInt)->goCheck();
        if ($id == 1) {
            return app('json')->fail('超级管理组不能删除');
        }
        $result = GroupModel::where('id', $id)->delete();
        if ($result) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 获取所有的分组
     * @return null|\think\Collection
     */
    public function getAllGroup()
    {
        $res = GroupModel::field('id,name,rule', true)->select();
        foreach ($res as $k=>$v){
            $res[$k]['rule']=explode(',',$v['rule']);
        }
        return app('json')->success($res);
    }

    /**
     * 获取某个分组详情
     * @param $id
     * @return int|\think\response\Json
     */
    public function getOneGroup($id)
    {
        (new IDPostiveInt())->goCheck();
        $data = GroupModel::find($id);
        if (!$data) {
            return app('json')->fail();
        }
        return app('json')->success($data);
    }

    /**
     * 获取所有的分组规则
     * @return null|\think\Collection
     */
    public function getAllGroupRule()
    {
        $res = Db::name('group_rule')->select();
        return app('json')->success($res);
    }

}