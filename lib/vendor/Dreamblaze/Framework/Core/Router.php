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
use Dreamblaze\GenericLogger\Logger;

class Router {
    public static $default = array(
        'controller' => 'HomeController',
        'action' => 'index'
    );

    public static $action;
    public static $controller;
    public static $parameters;

    private static $routes = array();

    public static function add_route($route){
        self::$routes[] = $route;
    }

    public static function add_routes($routes){
        self::$routes += $routes;
    }

    public static function init(){
        self::$parameters = self::parse_url();

        $controller_name = "\\" . \Dreamblaze\Helpers\Toolbox::to_camel_case(self::$parameters['controller'], true) . 'Controller';
        $action = self::$parameters['action'];

        if(!class_exists($controller_name))
            throw new RouteException("Controller $controller_name doesn't exist");

        if(!method_exists($controller_name, $action))
            throw new RouteException("Action '$action' on $controller_name doesn't exist");

        Logger::debug("Executing $action on $controller_name", 'Router');
        Logger::debug(self::$parameters, 'Router');
        self::$action = $action;
        self::$controller = new $controller_name();
    }

    public static function route(){
        $params = array_merge(self::$parameters,$_REQUEST);
        self::$controller->execute(self::$action, $params);
    }

    private static function parse_url(){
        return self::dispatcher(Kernel::$request->relative_url, array(
           new Route('/:controller/show/:id', array('controller' => ':controller', 'action' => 'show', 'id' => ':id')),
           new Route('/:controller/:action/:id', array('controller' => ':controller', 'action' => ':action', 'id' => ':id')),
           new Route('/:controller/:action', array('controller' => ':controller', 'action' => ':action')),
           new Route('/:controller', array('controller' => ':controller')),
        ));
    }

    private static function dispatcher($url, $routes)
    {
        $url_parts = explode('/', $url);
        $url_parts_count = count($url_parts);
        $result = null;

        foreach($routes as $route){
            $arguments = $route->arguments;
            $pattern_parts = explode('/', $route->pattern);
            array_shift($pattern_parts);
            if(count($pattern_parts) <> $url_parts_count)
                continue;

            foreach($pattern_parts as $i=>$pattern_part){

                if(substr($pattern_part,0,1) == ':'){ //is wildcard
                    $arg_key = array_search($pattern_part, $arguments);
                    if($arg_key != null){
                        if($url_parts[$i] != '')
                            $arguments[$arg_key] = $url_parts[$i];
                        else
                            continue 2;
                    } else {
                        throw new RouteException("No matching placeholder found for " . $pattern_part);
                    }
                } elseif($url_parts[$i] != $pattern_part) { //constant doesn't exist
                    continue 2;
                }
            }
            $result = $arguments;
            break;
        }
        if($result != null)
            return $result;
        else
            throw new RouteException("No Route found");
    }

}
