<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/24 0024
 * Time: 13:43
 */

namespace app\model;


use bases\BaseModel;

class DeliveryRule extends BaseModel
{

    /**
     * 地区集转为数组格式
     * @param $value
     * @return array
     */
    public function getRegionAttr($value)
    {
        $value = explode(',', $value);
        return $value;
    }


    /**
     * 添加模板区域及运费
     * @param $data
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function createDeliveryRule($data,$id='')
    {
        self::where('delivery_id',$id)->delete();
        foreach ($data as $k=>$v){
            $rule['delivery_id']=$id;
            $rule['region']=implode(',',$v['region']);
            $rule['first']=$v['first'];
            $rule['first_fee']=$v['first_fee'];
            $rule['additional']=$v['additional'];
            $rule['additional_fee']=$v['additional_fee'];
            self::create($rule);
        }
    }

    /**
     * 可配送区域
     * @param $value
     * @param $data
     * @return string
     */
    public static function getRegionContentAttr($value, $data)
    {
        // 当前区域记录转换为数组
        $regionIds = explode(',', $data['region']);

        if (count($regionIds) === 373) return '全国';

        // 所有地区
        if (empty(self::$regionAll)) {
            self::$regionAll = Region::getCacheAll();
            self::$regionTree = Region::getCacheTree();
        }
        // 将当前可配送区域格式化为树状结构$alreadyTree = [];
        foreach ($regionIds as $regionId)
            $alreadyTree[self::$regionAll[$regionId]['pid']][] = $regionId;
        $str = '';
        foreach ($alreadyTree as $provinceId => $citys) {
            $str .= self::$regionTree[$provinceId]['name'];
            if (count($citys) !== count(self::$regionTree[$provinceId]['city'])) {
                $cityStr = '';
                foreach ($citys as $cityId)
                    $cityStr .= self::$regionTree[$provinceId]['city'][$cityId]['name'];
                $str .= ' (<span class="am-link-muted">' . mb_substr($cityStr, 0, -1, 'utf-8') . '</span>)';
            }
            $str .= '、';
        }
        return mb_substr($str, 0, -1, 'utf-8');
    }

}