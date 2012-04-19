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
    public $params = array();
    public $url;
    public $raw;
    public $ref;
    public $base_url;
    public $host;
    public $rooturl;

    public function __construct() {
        $this->host = $this->get_host();
        $this->params = $this->get_params();
        $this->url = $this->get_url();
        $this->raw = $this->get_raw();
        $this->base_url = $this->get_base_url();
        $this->ref = $this->get_ref();
        $this->rooturl = $this->get_rooturl();
    }

    private function get_host(){
        return $_SERVER['SERVER_NAME'];
    }

    private function get_url() {
        if(isset($_REQUEST['url'])){
           $url = $_REQUEST['url'];
        } else {
           $url = '';
        }
        
        if (substr($url, 0, 1) !== '/') {
            $url = '/' . $url ;
        }
        return $url;
    }

    private function get_raw(){
        return explode('/', $this->url );
    }

    private function get_params() {
        $params = array();
        foreach($_GET as $key=>$val){
            if($key != 'url')
                $params[$key] = urldecode($val);
        }
        $params += $_POST;
        return $params;
    }

    private function get_ref() {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "") {
            $domain = parse_url($_SERVER['HTTP_REFERER']);
            if ($domain['host'] == $this->host) {
                return $_SERVER['HTTP_REFERER'];
            } else {
                return $this->base_url;
            }
        } else {
            return $this->base_url;
        }
    }
    
    private function get_base_url() {
        try{
            return $this->rooturl . Environment::get_value('app_url_base');
        } catch(Exception $e) {
            return $this->rooturl;
        }
    }

    private function get_rooturl() {
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
