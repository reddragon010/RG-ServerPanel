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
    //TODO: Deep coupling
    public static function setup(){
        $filelogger = new Logger\FileLogger(ROOT . '/logs/core.log');
        self::register_observer($filelogger);

        if(Config::instance('framework')->get_value('debug')){
            try{
                $opts = Config::instance('framework')->get_value('debugopts');
            } catch(\Exception $e) {
                $opts = array();
            }

            $fblogger = new Logger\FirePhpLogger($opts);
            self::register_observer($fblogger);
        }
        self::init(Config::instance('framework')->get_value('loglevel'));
    }
}
