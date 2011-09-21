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

class Framework_Autoloader {
    
    static private $loadpaths = array(
        'FRAMEWORK' => array(
            '/core/',
            '/lib/'
        ),
        'APP' => array(
            '/controllers/',
            '/models/',
            '/viewextentions/'
        )
    );
    
    static public function register() {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    static public function autoload($class) {
        foreach(self::$loadpaths as $region => $paths){
            $root_path = constant($region . '_ROOT');
            foreach($paths as $path){
                $fullpath = $root_path . $path . self::class_to_filename($class);
                if(file_exists($fullpath)){
                    require $fullpath;
                }
            }
        }
    }
    
    static private function class_to_filename($class){
        $class = str_replace('_','/',$class);
        $class = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class));
        return $class . '.php';
    }

}