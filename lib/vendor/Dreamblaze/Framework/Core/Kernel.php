<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.04.12
 * Time: 04:21
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Framework\Core;

class Kernel
{
    public static $request;

    public static function init(){
        ob_start();

        self::$request = new Request();
        Router::init();
        Router::route();
    }

    public static function send(){
        ob_end_flush();
    }
}
