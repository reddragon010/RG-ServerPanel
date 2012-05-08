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
use Symfony\Component\Yaml\Yaml;
use Dreamblaze\GenericLogger\Logger;

class I18n{
    private static $l = array();
    private static $lang;

    public static function load(){
        self::$lang = Environment::get_value('lang');
        if(self::cache_uptodate())
            self::load_from_cache();
        else
            self::load_from_file();
        self::save_to_cache();
    }
    
    public static function get(){
        $keys = func_get_args();
        $tmp = self::$l;
        foreach($keys as $key){
            if(is_array($tmp) && isset($tmp[$key]))
                $tmp = $tmp[$key];
        }
        if(empty($tmp) || is_array($tmp)){
            $tmp = '$' . implode(' - ', $keys) . '$';
        }
        return $tmp;
    }

    private static function load_from_file(){
        foreach(glob(self::get_file_path()) as $filename){
            $l = Yaml::parse($filename);
            self::$l = array_merge_recursive((array)self::$l, (array)$l);
        }
    }

    private static  function load_from_cache(){
        self::$l = unserialize(file_get_contents(self::get_cache_path()));
    }

    private static  function save_to_cache(){
        if(!self::cache_uptodate()){
            file_put_contents(self::get_cache_path(),serialize(self::$l));
            touch(self::get_file_path());
            Logger::debug("Rewriting Cache", 'i18n');
        }
    }

    private static function cache_uptodate(){
        return file_exists(self::get_cache_path());
    }

    private static function get_cache_path(){
        return FRAMEWORK_ROOT . "/cache/" . self::$lang . ".yml.cache";
    }

    private static function get_file_path(){
        return APP_ROOT . "/lang/" . self::$lang . "/*.yml";
    }
}

?>
