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

class Environment {
    public static $name;
    
    private function __construct() {}
    
    public static function setup($name) {
        self::$name = $name;
        if(!Config::exists('envs')) throw new Exception("Environment-Config not found");
        Config::instance('envs')->get_value($name);
        self::set_timezone();
    }
    
    public static function get_value(/* key_level1, key_level2*/) {
        $keys = array_merge(array(self::$name),func_get_args());
        $config = Config::instance('envs');
        return call_user_func_array(array($config, 'get_value'), $keys);
    }

    private static function set_timezone(){
        try{
            $timezone = self::get_value('timezone');
        } catch(Exception $e) {
            $timezone = 'Europe/Vienna';
        }
        date_default_timezone_set($timezone);   
    }
    
    public static function trigger_error($msg, $level){
        trigger_error($msg, $level);
    }

}
