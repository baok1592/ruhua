<?php

namespace services;

use exceptions\BaseException;
use think\Exception;

//策略模式工厂
class QyFactory
{
    private $binds = [];
    public function instance($name)
    {
        if (isset($this->binds[$name]) ) {
            return $this->binds[$name];
        }
        try {
            $class = new \ReflectionClass("app\services\\".$name);
            $this->binds[$name] = $class->newInstance();
            return  $this->binds[$name];
        } catch ( Exception $e) {
            throw new BaseException();
        }
    }
}