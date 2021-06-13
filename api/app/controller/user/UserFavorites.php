<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 13:39
 */

namespace app\controller\user;


use app\model\Favorites as FavoritesModel;
use app\services\TokenService;
use app\validate\FavValidate;
use app\validate\IDPostiveInt;
use bases\BaseController;

class UserFavorites extends BaseController
{
    protected $beforeActionList = [
        'checkScope'
    ];

    /**
     * 查询某商品是否已收藏
     * @param string $id
     * @param string $type
     * @return mixed
     */
    public function scFavGood($id = '', $type = 'goods')
    {
        (new IDPostiveInt())->goCheck();
        $uid = TokenService::getCurrentUid();//接收token，获取uid
        $res = FavoritesModel::getFavTypeByID($id, $uid, $type);
        return app('json')->success($res);
    }

    /**
     * 获取该用户所有收藏
     * @return \think\response\Json
     */
    public function getFavorite()
    {
        $uid = TokenService::getCurrentUid();//接收token，获取uid
        return FavoritesModel::getFavoriteByUid($uid);
    }

    /**
     * 添加收藏
     * @return \think\response\Json
     */
    public function addFavorite()
    {
        $validate = new FavValidate();
        $validate->goCheck();
        $data = $validate->getDataByRule(input('post.'));//过滤非验证器中的字段
        $data['uid'] = TokenService::getCurrentUid();//接收token，获取uid
        return FavoritesModel::addFavorites($data);
    }

    /**
     * 删除收藏
     * @param string $id
     * @return mixed
     */
    public function deleteFavorite($id = '')
    {
        (new IDPostiveInt)->goCheck();
        $uid = TokenService::getCurrentUid();
        $res = FavoritesModel::where(['fav_id' => $id, 'uid' => $uid])->delete();
        if ($res) {
            return app('json')->fail();
        }
        return app('json')->success();

    }

}