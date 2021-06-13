<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31 0031
 * Time: 16:55
 */

namespace app\controller\cms;


use app\model\FxGoods as FxGoodsModel;
use app\model\FxRecord as FxRecordModel;
use app\services\StatisticService;
use app\validate\IDPostiveInt;
use bases\BaseController;

class FxManage extends BaseController{

    /**
     * 设置分销商品
     * @return mixed
     */
    public function addFxGoods(){
        $post=input('post.');
        return FxGoodsModel::addGoods($post);
    }

    /**
     * cms获取分销商品
     * @param $type
     * @return array|\think\Collection
     */
    public function getFxGoods($type){
        return FxGoodsModel::getGoods($type);
    }

    /**
     * 修改分销提成价格
     * @return mixed
     */
    public function editFxGoods(){
        $rule=[
            'id'=>'require',
            'price'=>'require'
        ];
        $post=input('post.');
        $this->validate($post,$rule);
        return FxGoodsModel::editPrice($post['id'],$post['price']);
    }

    /**
     * 删除分销商品
     * @param string $id
     * @return mixed
     */
    public function delFxGoods($id=''){
        (new IDPostiveInt())->goCheck();
        return FxGoodsModel::deleteFxGoods($id);
    }

    /**
     * cms获取分销记录
     * @return mixed
     */
    public function getFxRecord(){
        if(app('system')->getValue('fx_status')==0){
            return app('json')->fail('未开启分销活动');
        }
        return FxRecordModel::getRecord();
    }

    /**
     * 获取分销统计排行
     * @return mixed
     */
    public function countFx(){
        return StatisticService::countFx();
    }

    /**
     * 手动打款成功修改状态
     * @param $id
     * @return mixed
     */
    public function editTxApply($id){
        (new IDPostiveInt)->goCheck();
        return FxRecordModel::agreeTxApply($id);
    }


    /**
     * 用户获取分销商品
     * @return array|\think\Collection
     */
    public function selectFxGoods(){
        return FxGoodsModel::selectFxGoods();
    }

}