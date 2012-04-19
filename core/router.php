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

class Router {
    public static $default = array(
        'controller' => 'home', 
        'action' => 'index'
    );

    public static $route;
    
    private static function get_controller(){
        $raw = Kernel::$request->raw;
        if(empty($raw[1])){
            throw new Exception("No Route found");
        } else {
            $controller = Toolbox::to_camel_case($raw[1], true) . 'Controller';
        }
        if (class_exists($controller)) {
            return new $controller();
        } else {
            //TODO exception handling
            throw new Exception("No Route found");
        }
    }
    
    private static function get_action(){
        $raw = Kernel::$request->raw;
        if (empty($raw[2])) {
            throw new Exception("No Route found");
        } else {
            $action = $raw[2];
        }
        return $action;
    }

    public static function get_route(){
        $controller = self::get_controller();
        $action = self::get_action();

        $route = new Route($controller,$action);
        GenericLogger::debug($route);
        self::$route = $route;
        return $route;
    }
}
