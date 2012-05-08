<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of StupidPrlf.
 *
 *    StupidPrlf is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    StupidPrlf is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with StupidPrlf.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Dreamblaze\Framework\Core;

class Environment {
    public static $name;
    
    private function __construct() {}
    
    public static function setup() {
        $env = getenv('ENV_NAME');
        if (empty($env)) {
            $env = 'default';
        }

        self::$name = $env;
        Config::instance('envs')->get_value($env);
        self::set_timezone();

        if (Environment::get_value('debug')) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        }
    }
    
    public static function get_value(/* key_level1, key_level2*/) {
        $keys = array_merge(array(self::$name),func_get_args());
        $config = Config::instance('envs');
        return $config->get_value($keys);
    }

    private static function set_timezone(){
        try{
            $timezone = self::get_value('timezone');
        } catch(\Exception $e) {
            $timezone = 'Europe/Vienna';
        }
        date_default_timezone_set($timezone);   
    }
    
    public static function trigger_error($msg, $level){
        trigger_error($msg, $level);
    }

}
