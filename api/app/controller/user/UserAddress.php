<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 16:36
 */

namespace app\controller\user;

use app\model\UserAddress as UserAddressModel;
use app\services\TokenService;
use app\validate\AddressValidate;
use app\validate\IDPostiveInt;
use bases\BaseController;

class UserAddress extends BaseController
{
    /**
     * 创建用户地址
     * 【当前模式是1人只能有1个地址】
     * 1、验证字段
     * 2、根据Token来获取uid
     * 3、根据uid来查找用户数据，判断用户是否存在，如果不存在抛出异常。
     * 4、获取用户从客户端提交来的地址信息，并过滤非验证器中的字段
     * 6、新增后成功返回
     */
    public function addAddress()
    {
        $validate = new AddressValidate();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();//接收token，获取uid
        $post = $validate->getDataByRule(input('post.'));//过滤非验证器中的字段
        return UserAddressModel::CreateOne($uid, $post);
    }

    /**
     * 删除指定地址
     * @param string $id
     * @return mixed
     */
    public function DeleteAddress($id = '')
    {
        (new IDPostiveInt)->goCheck();
        $uid = TokenService::getCurrentUid();
        $res = UserAddressModel::where(['id' => $id, 'user_id' => $uid])->delete();
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
        //        return $res?1:0;
    }

    /**
     * 更新指定地址
     *  @param $id
     * @return \think\response\Json
     */
    public function editAddress($id)
    {
        (new IDPostiveInt)->goCheck();
        (new AddressValidate())->goCheck();;
        $post=input('post.');
        $uid= TokenService::getCurrentUid();//接收token，获取uid
        return UserAddressModel::editAddress($id,$uid,$post);
    }


    /**
     * 获取用户所有地址
     * @return mixed
     */
    public function getAllAddress()
    {
        $uid = TokenService::getCurrentUid();//接收token，获取uid
        $data = UserAddressModel::where('user_id', $uid)->select();
        return app('json')->success($data);
//        return $data;
    }

    /**
     * 获取指定地址
     * @param string $id
     * @return mixed
     */
    public function getOneAddress($id = '')
    {
        (new IDPostiveInt)->goCheck();
        $uid = TokenService::getCurrentUid();
        return UserAddressModel::getAddressOne($id, $uid);
    }

    /**
     * 获取用户默认地址
     * @return \think\response\Json
     */
    public function getAddressDefault()
    {
        $uid = TokenService::getCurrentUid();
        $res = UserAddressModel::where(['is_default' => '1', 'user_id' => $uid])->find();
        if (!$res) {
            return app('json')->fail('地址不存在');
        }
        return app('json')->success($res);
//        return $res;
    }

    /**
     * 将用户指定地址设为默认
     * @param string $id
     * @return mixed
     */
    public function setUserAddressDefault($id='')
    {
        (new IDPostiveInt())->goCheck();
        $uid = TokenService::getCurrentUid();
        $res = UserAddressModel::setOnlyAddress($id, $uid);
        if (!$res) {
            return app('json')->fail('默认地址设置失败');
        }
        return app('json')->success();
    }

    public function getAllRegion(){

    }
}