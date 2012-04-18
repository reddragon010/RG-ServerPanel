<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 18.04.12
 * Time: 22:30
 * To change this template use File | Settings | File Templates.
 */

$filelogger = new FileLogger(FRAMEWORK_ROOT . '/logs/core.log');
GenericLogger::register_observer($filelogger);

if(Environment::get_value('debug')){
    try{
        $opts = Environment::get_value('debugopts');
    } catch(Exception $e) {
        $opts = array();
    }

    $fblogger = new FirephpLogger($opts);
    GenericLogger::register_observer($fblogger);
}
GenericLogger::init(Environment::get_value('loglevel'));