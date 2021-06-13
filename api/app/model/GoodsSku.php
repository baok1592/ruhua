<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 9:42
 */

namespace app\model;


use bases\BaseModel;

class GoodsSku extends BaseModel
{
    protected $visible=['sku_id','sku_name','json'];

    /**
     * 关联模型
     * @return \think\model\relation\BelongsTo
     */
    public function imgs()
    {
        return $this->belongsTo('Image','picture','id');
    }

    public function getJsonAttr($v){
        return json_decode($v,true);
    }

    /**
     * @param $goods_id
     * @param $arr
     * @param $sku_img_ids
     * @return int
     */
    public function addSku($goods_id,$arr,$sku_img_ids){
        $sarr = $this->set_kv_arr($arr, $sku_img_ids);  //sku_tree
        $fdata = $this->set_kv_json($arr, $sarr);   //sku_list
        $ty['tree'] = $sarr;
        $ty['list'] = $fdata;
        $json = json_encode($ty, JSON_UNESCAPED_UNICODE);
        $res=self::create([
            'json' => $json,
            'goods_id' => $goods_id
        ]);
        return $res['id'];
    }

    /**
     * 修改sku
     * @param $goods_id
     * @param $arr
     * @param $sku_img_ids
     */
    public function editSku($goods_id,$arr,$sku_img_ids){
        $sarr = $this->set_kv_arr($arr, $sku_img_ids);  //sku_tree
        $fdata = $this->set_kv_json($arr, $sarr);   //sku_list
        $ty['tree'] = $sarr;
        $ty['list'] = $fdata;
        $json = json_encode($ty, JSON_UNESCAPED_UNICODE);
        $gsku = self::where('goods_id',$goods_id)->find();
        if ($gsku) {
            self::where('goods_id',$goods_id)->update(['json'=>$json]);
        } else {
            self::create([
                'json' => $json,
                'goods_id' => $goods_id
            ]);
        }
    }


    /**
     * sku_tree
     * @param $arr
     * @param string $imgs
     * @return array
     */
    public function set_kv_arr($arr, $imgs = '')
    {
        $obj = [];
        foreach ($arr as $k => $v) {
            foreach ($v as $kk => $vv) {
                if (!in_array($kk, ['price', 'stock_num'])) {
                    $obj[$kk][$k] = $vv;
                }
            }
        }
        $sarr = [];
        $x = 0;
        foreach ($obj as $k => $v) {
            $sv = array_unique($v);//去重复
            $sarr[$x]['k'] = $k;
            $sx = array_values($sv);//去除键名
            foreach ($sx as $kk => $vv) {
                $sarr[$x]['v'][$kk]['id'] = ($x + 1) . '0' . $kk;
                $sarr[$x]['v'][$kk]['name'] = $vv;
                if ($x == 0 && isset($imgs[$kk])) {
                    $sarr[$x]['v'][$kk]['img_id'] = $imgs[$kk];
                }
            }
            $sarr[$x]['k_s'] = 's' . ($x + 1);
            $x++;
        }
        return $sarr;
    }

    /**
     * sku_list
     * @param $arr
     * @param $sarr
     * @return array
     */
    public function set_kv_json($arr, $sarr)
    {
        $ca = [];
        foreach ($arr as $k => $v) {
            $x = 0;
            foreach ($v as $kk => $vv) {
                if (!in_array($kk, ['price', 'stock_num'])) {
                    $ca[$k][$x]['k'] = $kk;
                    $ca[$k][$x]['v'] = $vv;
                    $x++;
                }
            }
            $ca[$k]['price'] = $v['price'];
            $ca[$k]['stock_num'] = $v['stock_num'];
        }
        $fdata = [];
        foreach ($ca as $k => $v) {
            foreach ($v as $kk => $vv) {
                if ($kk !== 'price' && $kk !== 'stock_num') {
                    $x = $kk + 1;
                    $fdata[$k]['s' . $x] = $this->get_sku_num($vv['k'], $vv['v'], $sarr);
                    $fdata[$k]['s' . $x . '_name'] = $vv['v'];
                }
                $fdata[$k]['id'] = $k + 1;
                $fdata[$k]['price'] = $v['price'];
                $fdata[$k]['stock_num'] = $v['stock_num'];
            }
        }
        return $fdata;
    }

    /**
     * 用规格名称查询规格编号
     * @param string $sk
     * @param string $sv
     * @param $sarr
     * @return int
     */
    public function get_sku_num($sk='颜色',$sv='黑',$sarr)
    {
        foreach ($sarr as $k=>$v){
            if($v['k']==$sk){
                foreach ($v['v'] as $kk=>$vv){
                    if($vv['name']==$sv){
                        return $vv['id'];
                    }
                }
            }
        }
        return 0;
    }


    //获取规格价格
    public static function getSkuPrice($goods, $sku_id)
    {
        foreach ($goods['goods_sku']['json']['list'] as $key => $value) {
            if ($sku_id == $value['id']) {
                return $value['price'];
            }
        }
    }
}