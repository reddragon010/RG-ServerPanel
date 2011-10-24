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

class Request {
    public static $controller;
    public static $action;
    public static $params = array();
    public static $url;
    public static $raw;
    public static $ref;
    public static $base_url;
    public static $host;
    
    private function __construct() {}
    
    public static function init() {
        self::parse_request();
        self::set_controller();
        self::set_action();
        self::set_params();
        self::$host = $_SERVER['SERVER_NAME'];
        self::set_base_url();
        self::set_ref();
    }

    private static function parse_request() {
        if(isset($_REQUEST['url'])){
           self::$url = $_REQUEST['url']; 
        } else {
           self::$url = '';
        }
        
        if (substr(self::$url, 0, 1) !== '/') {
            self::$url = '/' . self::$url ;
        }
        self::$raw = explode('/', self::$url );
    }

    private static function set_controller() {
        if (empty(self::$raw[1])) {
            $controller = '';
        } else {
            $controller = self::$raw[1];
        }
        self::$controller = $controller;
    }
    
    private static function set_action() {
        if (empty(self::$raw[2])) {
            $action = '';
        } else {
            $action = self::$raw[2];
        }
        self::$action = $action;
    }

    private static function set_params() {
        foreach($_GET as $key=>$val){
            if($key != 'url')
                self::$params[$key] = urldecode($val);
        }
        self::$params += $_POST;
    }

    private static function set_ref() {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "") {
            $domain = parse_url($_SERVER['HTTP_REFERER']);
            if ($domain['host'] == self::$host) {
                self::$ref = $_SERVER['HTTP_REFERER'];
            } else {
                self::$ref = self::$base_url;
            }
        } else {
            self::$ref = self::$base_url;
        }
    }
    
    private static function set_base_url() {
        try{
            self::$base_url = self::find_rooturl() . Environment::get_value('app_url_base');
        } catch(Exception $e) {
            self::$base_url = self::find_rooturl();
        }
    }

    private static function find_rooturl() {
        $pageURL = 'http';

        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
            $pageURL .= "s";

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }

        return $pageURL;
    }

}
