<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 15:15
 */

namespace app\controller\user;


use app\model\Rate as RateModel;
use app\services\TokenService;
use app\validate\IDPostiveInt;
use bases\BaseController;

class UserRate extends BaseController
{



    /**
     * 获取某个商品的所有评价
     * @param $id
     * @return \think\response\Json
     */
    public function getGoodsRate($id)
    {
        (new IDPostiveInt())->goCheck();
        $pj=RateModel::with(['user'])->where('goods_id',$id)->order('id desc')->select();
        return app('json')->success($pj);
    }

}