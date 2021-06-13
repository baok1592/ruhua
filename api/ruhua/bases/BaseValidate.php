<?php

namespace bases;

use exceptions\BaseException;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
    //获取所有请求数据并做批量验证
    public function goCheck()
    {
        $params = Request::param();
        $result = $this->batch()->check($params);
        if (!$result){
           throw new BaseException(['msg' => $this->error]);
        }else{
            return true;
        }
    }

    //验证是否正整数
    protected function isPositiveInteger($value)
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    //验证是否为空
    protected function isNotEmpty($value)
    {
        if (empty($value))
        {
            return false;
        } else {
            return true;
        }
    }
    //验证提交的数组中不含user_id与uid。并且过滤非验证器规则的字段
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) |array_key_exists('uid', $arrays)){
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键

            throw new BaseException(['msg' => '参数中包含有非法的参数名user_id或者uid']);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
}
