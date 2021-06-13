<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29 0029
 * Time: 14:19
 */

namespace app\controller\cms;


use app\model\SysBackup as SysBackupModel;
use backup\Backsql;
use bases\BaseController;
use think\facade\Config;

class Backup extends BaseController
{
    /**
     * 添加备份数据库
     * @return mixed
     */
    public function addBackup()
    {
        $sql = new Backsql(Config::get("database"));
        return $sql->backup();
    }


    /**
     * 获取备份列表
     * @return mixed
     */
    public function getBackup(){
        $res=SysBackupModel::order('id','desc')->select();
        return app('json')->success($res);
    }

    /**
     * 删除备份
     * @param $id
     * @return mixed
     */
    public function deleteBackup($id){
        $res=SysBackupModel::where('id',$id)->find();
        $data=unlink(ROOT.$res['url']);
        $res->delete();
        if($data){
            return app('json')->success();
        }
        return app('json')->fail();
    }
}