<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 10:44
 */

namespace app\model;


use bases\BaseModel;

class Category extends BaseModel
{
    protected $updateTime = false;

    /**
     * 关联图片
     * @return \think\model\relation\BelongsTo
     */
    public function imgs()
    {
        return $this->belongsTo('Image', 'category_pic', 'id');
    }

    /**
     * 添加分类
     * @param $form
     * @return int
     */
    public static function addCategory($form)
    {
        if ($form['pid'] == 0) {
            $form['level'] = 1;
        } else {
            $form['level'] = 2;
        }
        $form['sort'] = 0;
        $res = self::create($form);
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 修改分组
     * @param $form
     * @return int
     */
    public static function editCategory($form)
    {
//        $form = json_decode($form, true);
        $form_data['category_id'] = $form['category_id'];
        $form_data['category_name'] = $form['category_name'];
        $form_data['short_name'] = $form['short_name'];
        $form_data['pid'] = $form['pid'];
        $form_data['category_pic'] = $form['category_pic'];
        if ($form['pid'] == 0) {
            $form_data['level'] = 1;
        } else {
            $form_data['level'] = 2;

        }
        $res = self::where(['category_id' => $form['category_id']])->update($form_data);
        if ($res) {
            $data = self::where(['category_id' => $form['category_id']])->find()->toArray();
            $img = Image::where('id',$data['category_pic'])->find();
            $data['imgs'] = $img['url'];
            $data['is_visible'] = $data['is_visible'] == 1 ? true : false;
            return app('json')->success($data);
        }
        return app('json')->fail();
    }

    /**
     * 删除分组
     * @param $id
     * @return int
     */
    public static function deleteCategory($id)
    {
        $c_ids=self::where('pid',$id)->column('category_id');
        $pid_goods = Goods::where('category_id', $id)->where('state',1)->count();
        $c_ids_goods = Goods::where(['category_id'=>$c_ids])->where('state',1)->count();
        if ($pid_goods > 0||$c_ids_goods>0) {
            return app('json')->fail('无法删除，该分类下有商品');
        }
        $result = self::where(['category_id' => $id])->delete();
        if (!$result) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 获取X级分类
     * @param $id
     * @return \think\Collection|void
     */
    public static function getCategoryLevel($id)
    {
        $where['level'] = $id;
        $where['is_visible'] = 1;
        $data = self::with('imgs')->where($where)->order('sort asc')->select();
        if (!$data || count($data) < 1) {
            return;//throw new BaseException(['msg'=>'获取商品分类失败或无数据']);app('json')->fail('获取商品分类失败或无数据');
        }
        return app('json')->success($data);
//        return $data;
    }

    /**
     * 获取所有分类信息
     * @param bool $vs
     * @return \think\Collection
     */
    public static function getCategoryAll($vs=false)
    {
        if($vs){
            $data=self::with('imgs')->order('sort asc')->select();
        }else{
            $data=self::with('imgs')->where('is_visible',1)->order('sort asc')->select();
        }

        if(!$data || count($data)<1){
            app('json')->fail('请至少添加一个商品分类');
        }
        return app('json')->success('操作成功',$data);
//        return $data;
    }

    /**
     * 获取分类下所有子类与广告图
     * @param $id
     * @return \think\response\Json
     */
    public static function getCateChildImg($id)
    {
        $data['category'] = self::with('imgs')->where('pid',$id)->select();
        $banner = Banner::with(['items','items.imgs'])->find($id+1);
        $data['banner'] = $banner['items'];
        return app('json')->success($data);
//        return  json($data);
    }


    /**
     * 排序
     * @param $arr
     * @return int
     */
    public static function setSort($arr){
        if(!is_array($arr)){
            return app('json')->fail();
        }
        foreach ($arr as $k=>$v){
            self::update(['sort'=>$v],['category_id'=>$k]);
        }
        return app('json')->success();
    }

    public function setImgsAttr($v)
    {
        return $v['url'];
    }
}