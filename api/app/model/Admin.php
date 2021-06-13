<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14 0014
 * Time: 13:55
 */

namespace app\model;




use bases\BaseModel;

class Admin extends BaseModel
{
    protected $hidden=['password'];

    public function group(){
        return $this->belongsTo('Group','group_id','id');
    }
}