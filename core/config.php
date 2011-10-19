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

class Config extends SingletonStore {
    protected $name;
    protected $content;

    protected function init($name){
        $this->name = $name;
        if(self::exists($name)){
            $this->content = sfYaml::load(CONFIG_ROOT . '/' . $name . CONFIG_ENDING);
        } else {
            throw new Exception("Config-File '$name' doesn't exist");
        }
    }
    
    public function get_value(/* key_level1, key_level2, ... */){
        $keys = array_filter(func_get_args());
        $tmp = $this->content;
        foreach($keys as $i=>$key){
            if(is_array($tmp) && isset($tmp[$key])){
                $tmp = $tmp[$key];
            }elseif(!$i != count($keys)){
                throw new Exception("Config-Key '".var_export($keys, true)."' not found");
            }
        }
        return $tmp;
    }
    
    public static function exists($name){
        return file_exists(CONFIG_ROOT . '/' . $name . CONFIG_ENDING);
    }
}
