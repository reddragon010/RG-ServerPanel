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

        Config::register_loadpath(ROOT.'/config/'.$env);

        $lang = Config::instance('framework')->get_value('lang');
        Config::register_loadpath(APP_ROOT.'/lang/'.$lang);

        self::$name = $env;

        self::set_timezone();
    }

    private static function set_timezone(){
        try{
            $timezone = Config::instance('framework')->get_value('timezone');
        } catch(\Exception $e) {
            $timezone = 'Europe/Vienna';
        }
        date_default_timezone_set($timezone);
    }
}
