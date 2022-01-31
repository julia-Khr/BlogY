<?php
namespace app\components;
class Helpers
{
    public static function pd($data)
    {
        echo  "<pre>";
        print_r($data);
        echo  "</pre>";
        die();
    }
    public static function pp($data)
    {
        echo  "<pre>";
        print_r($data);
        echo  "</pre>";

    }
}