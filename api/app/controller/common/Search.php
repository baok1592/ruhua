<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/20 0020
 * Time: 8:49
 */

namespace app\controller\common;

use app\model\Goods as GoodsModel;
use app\services\SearchService;
use bases\BaseController;
use think\facade\Cache;

class Search extends BaseController
{
    /**
     * 搜索商品
     * @param $name
     * @return mixed
     */
    public function searchGoods($name)
    {
        $name=trim($name);
        $data=GoodsModel::getProductByName($name);
        if(strlen($name)<6){
            SearchService::setSearchCache($name);
        }
        return app('json')->success($data);
    }

    /**
     * 获取前十的搜索记录
     * @return mixed
     */
    public function getSearchRecord(){
        $res= SearchService::getSearchCache();
       return app('json')->success($res);
    }

    /**
     * cms新增记录
     * @param $name
     * @param $num
     * @return mixed
     */
    public function addSearchGoods($name,$num=1){
        $post=input('post.');
        $this->validate($post,['name'=>'require','num'=>'require|number']);
        $name=trim($name);
        SearchService::setSearchCache($name,$num);
        return app('json')->success();
    }

    /**
     * 删除搜索记录
     * @param $name
     * @return mixed
     */
    public function deleteSearchGoods($name){
       return SearchService::deleteSearchCache($name);
    }

}