<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29 0029
 * Time: 14:20
 */

namespace app\model;


use bases\BaseModel;

class SysBackup extends BaseModel
{
    public static function addBackup($name,$size)
    {
        $data['name']=$name;
        $data['size']=$size;
        $data['url']='/backup/'.$name;
        self::create($data);
    }
}