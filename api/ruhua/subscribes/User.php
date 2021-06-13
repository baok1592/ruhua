<?php
namespace subscribes;


class User
{
    public static function one()
    {
        echo 'one';
    }
    public function onShow()
    {
        echo 'show';
    }

    public function onUserLogout()
    {
        echo '2';
    }

}