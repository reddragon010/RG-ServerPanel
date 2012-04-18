<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 18.04.12
 * Time: 22:30
 * To change this template use File | Settings | File Templates.
 */

$filelogger = new FileLogger(FRAMEWORK_ROOT . '/logs/core.log');
Logger::register_observer($filelogger);

if(Environment::get_value('debug')){
    try{
        $phpdebug_opts = Environment::get_value('phpdebug');
    } catch(Exception $e) {
        $phpdebug_opts = array();
    }
    $uilogger = new UiLogger($phpdebug_opts);
    Logger::register_observer($uilogger);
}
Logger::init(Environment::get_value('loglevel'));