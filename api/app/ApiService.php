<?php

namespace app;

use utils\System;
use utils\Json;
use think\Service;

class ApiService extends Service
{
    public function register()
    {
        $this->app->bind( 'json',Json::class);
        $this->app->bind( 'system',System::class);
    }

    public function boot()
    {

    }
}
