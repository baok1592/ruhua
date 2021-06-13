<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 13:44
 */

namespace app\controller\cms;


use app\model\BannerItem as BannerItemModel;
use app\validate\IDPostiveInt;
use bases\BaseController;
use services\QyFactory;

class BannerManage extends BaseController
{

    /**
     * 添加广告
     * @return int
     */
    public function addBanner()
    {
        $rule = ([
            'img_id' => 'require|number',
            'banner_id' => 'require|number',
            'key_word' => 'require|chsAlphaNum'
        ]);
        $post = input('post.');
        $this->validate($post, $rule);
        return BannerItemModel::addBanner($post);
    }

    /**
     * 删除广告
     * @param $id
     * @return int
     */
    public function deleteBanner($id)
    {
        (new IDPostiveInt)->goCheck();
        $res = BannerItemModel::where('id', $id)->delete();
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
//        return $res ? 1 : 0;
    }

    /**
     * 修改广告
     * @return int
     */
    public function editBanner()
    {
        (new IDPostiveInt)->goCheck();
        $post = input('post.');
        $res = BannerItemModel::update($post);
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 更新排序
     * @return int
     */
    public function setSort()
    {
        $arr = input('post.');
        return BannerItemModel::setSort($arr);
    }

    /**
     * cms获取所有广告
     * @return mixed
     */
    public function adminAllBanner()
    {
        $article = (new QyFactory())->instance('CmsService');
        $data = $article->get_banner_list();
        return app('json')->success($data);
//        return json($data);
    }

}