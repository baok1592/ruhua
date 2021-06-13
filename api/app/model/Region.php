<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/24 0024
 * Time: 16:10
 */

namespace app\model;


use bases\BaseModel;

class Region extends BaseModel
{
    /**
     * 获取所有地区树状图
     * @param int $type 默认查2级城市，3查询到3级城市
     * @return array
     */
    public static function getRegion($type)
    {
        // 所有地区
        $allData = self::column('id, pid, name, level', 'id');
        // 格式化
        $tree = [];
        foreach ($allData as $pKey => $province) {
            if ($province['level'] == 1) {    // 省份
                $tree[$pKey] = $province;
                unset($allData[$pKey]);
                foreach ($allData as $cKey => $city) {
                    if ($city['level'] == 2 && $city['pid'] == $province['id']) {    // 城市
                        $tree[$pKey]['children'][$city['id']] = $city;
                        unset($allData[$cKey]);
                        if ($type == 3) {
                            foreach ($allData as $rKey => $region) {
                                if ($region['level'] == 3 && $region['pid'] == $city['id']) {    // 地区
                                    $tree[$province['id']]['city'][$city['id']]['region'][$region['id']] = $region;
                                    unset($allData[$rKey]);
                                }
                            }
                        }
                    }
                }
            }
        }
        $arr=[];
        $i=0;
        foreach ($tree as $k=>$v){
            $data['id']=$v['id'];
            $data['label']=$v['name'];
            $data['children']=[];
            array_push($arr,$data);
            foreach ($v['children'] as $key=>$value){
                $res['id']=$value['id'];
                $res['label']=$value['name'];
                array_push($arr[$i]['children'],$res);
            }
            $i++;
        }
        return $arr;
    }
}