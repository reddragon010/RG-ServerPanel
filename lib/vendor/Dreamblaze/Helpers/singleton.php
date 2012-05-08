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

namespace Dreamblaze\Helpers;

abstract class Singleton {
    private static $instances = array();
    
    public static function instance(){
        $class = get_called_class();
        if(!isset(self::$instances[$class])){
            self::$instances[$class] = new $class();
            if (method_exists($class, 'init')) {
                if (func_num_args() > 0) {
                    $args = func_get_args();
                    call_user_func_array(array(self::$instances[$class], 'init'), $args);
                } else {
                    call_user_func(array(self::$instances[$class], 'init'));
                }
            }
        }
        return self::$instances[$class];
    }
    
    final protected function __construct(){}
}
