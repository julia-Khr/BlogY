<?php

use Yii;
use yii\console\Controller;

class MyFunc extends Controller 
{
    public static function pD($data) 
    {
        
        echo '<pre>' . print_r($data) . '</pre>';
                    
       
    }
}