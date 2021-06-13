<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22 0022
 * Time: 9:03
 */

namespace app\controller\common;


use bases\BaseController;
use app\model\Banner as BannerModel;
use app\model\BannerItem as BannerItemModel;
use app\validate\IDPostiveInt;
use services\QyFactory;

class Banner extends BaseController
{
    /**
     * 获取某个广告
     * @param $id
     * @return \think\response\Json
     */
    public function getBannerItem($id)
    {
        return BannerModel::getBannerByID($id);
    }

    /**
     * 获取所有广告位
     * @return \think\Collection
     */
    public function getAllBanner()
    {
        $data = BannerModel::select();
        return app('json')->success($data);
//        return $data;
    }

    /**
     * 获取所有广告
     * @return mixed
     */
    public function banner_all_item()
    {
        $article=(new QyFactory())->instance('UserService');
        $data=$article->get_banner_list();
        return app('json')->success($data);
//        return json($data);
    }


    /**
     * 获取广告详细
     * @param $id
     * @return \think\response\Json
     */
    public function get_banner_content($id)
    {
        (new IDPostiveInt)->goCheck();
        $data = BannerItemModel::with('imgs')->find($id);
        if (!$data) {
            return app('json')->fail('广告不存在');
        }
        return app('json')->success($data);
//        return json($data);
    }

}