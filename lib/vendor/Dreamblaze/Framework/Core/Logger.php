<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 08.05.12
 * Time: 00:28
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Framework\Core;

class Logger extends \Dreamblaze\GenericLogger\Logger
{
    public static function setup(){
        $filelogger = new Logger\FileLogger(FRAMEWORK_ROOT . '/logs/core.log');
        self::register_observer($filelogger);

        if(Environment::get_value('debug')){
            try{
                $opts = Environment::get_value('debugopts');
            } catch(\Exception $e) {
                $opts = array();
            }

            $fblogger = new Logger\FirePhpLogger($opts);
            self::register_observer($fblogger);
        }
        self::init(Environment::get_value('loglevel'));
    }
}
