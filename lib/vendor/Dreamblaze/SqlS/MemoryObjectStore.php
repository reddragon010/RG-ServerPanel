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

namespace Dreamblaze\SqlS;

class MemoryObjectStore {
    private static $store = array();
    
    public static function gen_key($object){
        if(is_string($object)){
            return $object;
        } elseif(is_numeric($object)){
            return strval($object);
        } else {
            return json_encode($object);
        }
    }
    
    public static function put($key,$value){
        $hash = self::gen_hash($key);
        self::$store[$hash] = $value;
    }
    
    public static function get($key){
        $hash = self::gen_hash($key);
        if(isset(self::$store[$hash])){
            $op = self::$store[$hash];
        } else {
            $op = false;
        }
        return $op;
    }
    
    public static function delete($key){
        $hash = self::gen_hash($key);
        if(isset(self::$store[$hash]))
                unset(self::$store[$hash]);
    }
    
    public static function pop($key){
        $op = self::get($key);
        self::delete($key);
        return $op;
    }
    
    public static function check($key){
        $hash = self::gen_hash($key);
        return isset(self::$store[$hash]);
    }
    
    private static function gen_hash($key){
        return crc32($key);
    }
}
