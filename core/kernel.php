<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.04.12
 * Time: 04:21
 * To change this template use File | Settings | File Templates.
 */
class Kernel
{
    public static $request;
    public static $app_controller;

    public static function init(){
        self::$request = new Request();
        self::$app_controller = new ApplicationController();
        Router::init();
        Router::route();
    }
}
