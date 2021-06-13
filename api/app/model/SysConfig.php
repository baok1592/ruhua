<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 8:52
 */

namespace app\model;


use bases\BaseModel;

class SysConfig extends BaseModel
{

    protected function setCreateTimeAttr($value)
    {
        return time();
    }

    protected function setUpdateTimeAttr($value)
    {
        return time();
    }

}