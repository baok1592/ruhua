<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/9 0009
 * Time: 13:22
 */

namespace app\controller\cms;


use app\model\VipTc as VipTcModel;
use app\validate\IDPostiveInt;
use bases\BaseController;
use kuaidi\Kd;

class VipManage extends BaseController
{
    /**
     * 添加vip套餐
     * return mixed
     */
    public function addVipTc(){
        $post=input('post.');
        $this->validate($post,['price'=>'require','title'=>'require','day_num'=>'require|number']);
        return VipTcModel::addVipTc($post);
    }

    /**
     * 删除vip套餐
     * @param $id
     * return mixed
     */
    public function deleteVipTc($id){
        (new IDPostiveInt())->goCheck();
        $res=VipTcModel::where('id',$id)->delete();
        return $res?app('json')->success():app('json')->fail();
    }

    /**
     * 获取vip套餐
     * return mixed
     */
    public function getVipTc(){
        $res=VipTcModel::select();
        return app('json')->success($res);
    }

}