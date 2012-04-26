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
        'controller' => 'HomeController',
        'action' => 'index'
    );

    public static $route;

    private static $raw;
    
    private static function get_controller(){
        if(empty(self::$raw[0])){
            $controller = self::$default['controller'];
        } else {
            $controller = Toolbox::to_camel_case(self::$raw[0], true) . 'Controller';
        }
        GenericLogger::debug("Lookup Controller " . $controller);
        if (class_exists($controller)) {
            return new $controller();
        } else {
            throw new RouteException("No Route found ($controller)");
        }
    }
    
    private static function get_action(){
        if (empty(self::$raw[1])) {
            $action = self::$default['action'];
        } else {
            $action = self::$raw[1];
        }
        return $action;
    }

    private static function parse_url(){
        return self::ay_dispatcher(Kernel::$request->relative_url, array(
           'test' => array(':controller', 'show', ':id'),
           'default' => array(':controller', ':action', ':id'),
           'default2' => array(':controller', ':action'),
           'default3'=> array(':controller'),

        ));
    }

    public static function get_route(){
        //var_dump(self::parse_url());
        //die('#');
        self::$raw = explode('/',Kernel::$request->relative_url);
        $controller = self::get_controller();
        $action = self::get_action();

        $route = new Route($controller,$action);
        GenericLogger::debug($route);
        self::$route = $route;
        return $route;
    }

    /**
     * @author Gajus Kuizinas <g.kuizinas@anuary.com>
     * @copyright Anuary Ltd, http://anuary.com
     * @version 1.0.0 (2011 12 06)
     */
    private static function ay_dispatcher($url, $routes)
    {
        $final_path         = FALSE;

        $url_path           = explode('/', $url);
        $url_path_length    = count($url_path);

        foreach($routes as $original_path => $filter)
        {
            // reset the parameters every time in case there is partial match
            $parameters     = array();

            // this filter is irrelevent
            if($url_path_length <> count($filter))
            {
                continue;
            }

            foreach($filter as $i => $key)
            {
                if(strpos($key, ':') === 0)
                {
                    $parameters[substr($key, 1)]    = $url_path[$i];
                }
                // this filter is irrelevent
                else if($key != $url_path[$i])
                {
                    continue 2;
                }
            }

            $final_path = $original_path;

            break;
        }

        return $final_path ? array('path' => $final_path, 'parameters' => $parameters) : FALSE;
    }

}
