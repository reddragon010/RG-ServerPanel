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

use Dreamblaze\Helpers\SingletonStore;
use Exception;
use Symfony\Component\Yaml\Yaml;

class Config {
    private static $store = array();
    private static $loadpaths = array();

    public static function register_loadpath($path){
        self::$loadpaths[] = $path;
    }

    /**
     * @static
     * @param $name
     * @return YamlFile
     */
    public static function instance($name){
        if(!isset(self::$store[$name]))
            self::$store[$name] = self::load($name);

        return self::$store[$name];
    }

    private static function load($name){
        foreach(self::$loadpaths as $loadpath){
            $filepath = $loadpath . DIRECTORY_SEPARATOR . $name . '.yml';
            if(file_exists($filepath)){
                return new YamlFile($name, $loadpath);
            }
        }
        var_dump(self::$loadpaths);
        throw new \Exception("Can't find Yaml-File '$name'");
    }
}
