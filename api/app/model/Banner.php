<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 13:45
 */

namespace app\model;


use bases\BaseModel;

class Banner extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time'];

    /**
     * 关联Item
     * @return \think\model\relation\HasMany
     */
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    /**
     * 获取某个广告
     * @param $id
     * @return \think\response\Json
     */
    public static function getBannerByID($id)
    {
        $data = self::with(['items', 'items.imgs'])->find($id);
        if (!$data) {
            return app('json')->fail('Banner不存在');
        }

        $art = [];
        foreach ($data['items'] as $k => $v) {
            $art[$v['sort']] = $v;
        }
        unset($data['items']);
        $data['items'] = $art;
        return app('json')->success($data);
//        return json($data);
    }


}