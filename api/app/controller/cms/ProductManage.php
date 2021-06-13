<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 10:06
 */

namespace app\controller\cms;


use app\model\Goods as GoodsModel;
use app\services\CopyProductService;
use app\validate\IDPostiveInt;
use app\validate\ProductValidate;
use bases\BaseController;

class ProductManage extends BaseController
{

    /**
     * cms 添加商品
     */
    public function addProduct()
    {
        $validate = new ProductValidate();
        $validate->goCheck();
        $post = $validate->getDataByRule(input('post.'));
//        $post=input('post.');
        return GoodsModel::addProduct($post);
    }

    /**
     * cms 修改商品
     * @return int
     */
    public function EditProduct()
    {
        $validate = new ProductValidate;
        $validate->rule('goods_id', 'require|number');//新增一个验证规则，不让其过滤goods_id
        $validate->goCheck();
        $post = input('post.');    //$validate->getDataByRule(input('post.'));//param数据对sku的操作会有问题
        return GoodsModel::editProduct($post);
    }

    /**
     * cms 删除商品
     * @param string $id
     * @return int
     */
    public function deleteProduct($id = '')
    {
        (new IDPostiveInt())->goCheck();
        $good_one = GoodsModel::where(['goods_id' => $id])->find();
        GoodsModel::deleteGoods($id);
        if(!$good_one){
            return app('json')->fail();
        }
        $result = $good_one->delete(config('setting.soft_del'));   //这里是软删除
        if (!$result) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 更新分类排序
     * @return int
     */
    public function setSort()
    {
        $arr = input('post.');
        return GoodsModel::setSort($arr);
    }

    /**
     * 获取所有上架商品，包含分页
     * @param string $key
     * @return \think\response\Json
     */
    public function getProduct($key = '')
    {
        return GoodsModel::getProductByPage($key);
    }

    /**
     * 获取所有下架商品
     * @return \think\response\Json
     */
    public function getProductsDown()
    {
        $data = GoodsModel::with('imgs')->where('state', 0)->order('create_time desc')->select();
        return app('json')->success($data);
    }

    /**
     * mcms获取所有商品简略
     * @return \think\Collection
     */
    public function all_goods_info()
    {
        $res = GoodsModel::with('imgs')->field('goods_id,goods_name,market_price,price,stock,sales,is_hot,is_new,state,img_id,sort')->select();
        return app('json')->success($res);
    }

    /**
     * mcms获取所有商品名称ID
     * @return \think\Collection
     */
    public function allGoodsName()
    {
        $res = GoodsModel::field('goods_id,goods_name')->select();
        return app('json')->success($res);
    }

    /**
     * 手机cms 修改商品
     * @return int
     */
    public function mEditProduct()
    {
        $rule = [
            'goods_id' => 'require',
            'goods_name' => 'require',//名称
            'stock' => 'require',  //库存
            'sales' => 'max:200',  //销量
            'market_price' => 'require|isNotEmpty', //市场价格
            'price' => 'require', //单价
        ];
        $post = input('post.');
        $this->validate($post, $rule);
        return GoodsModel::mobileEditProduct($post);
    }

    /**
     * 采集商品
     * @return \app\services\json
     */
    public function getCopyProductInfo(){
        return (new CopyProductService())->getRequestContents();
    }

    /**
     * 获取为参加活动的商品
     * @return mixed
     */
    public function getNormalGoods(){
        $res=GoodsModel::getNormalGoods();
        return app('json')->success($res);
    }

}