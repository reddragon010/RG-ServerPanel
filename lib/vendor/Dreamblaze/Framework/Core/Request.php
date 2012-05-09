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

class Request {
    public $params = array();
    public $relative_url;
    public $ref;
    public $host;
    public $root_url;
    public $current_url;
    public $protocol;

    public function __construct() {
        $this->protocol = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";

        $this->host = ($_SERVER["SERVER_PORT"] == "80") ?
            $_SERVER["SERVER_NAME"] :
            $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];

        $this->root_url = $this->protocol . '://' . $this->host;

        $this->load_relative_url();

        $this->current_url = $this->root_url . '/' . $this->relative_url;

        $this->load_params();

        $this->load_ref();
        Logger::debug($this);
    }

    private function load_relative_url() {
        $url = $_SERVER["REQUEST_URI"];
        
        if (substr($url, 0, 1) === '/') {
            $url = substr($url,1);
        }
        $this->relative_url = parse_url($url,PHP_URL_PATH);
    }

    private function load_params() {
        $params = array();
        foreach($_GET as $key=>$val){
            if($key != 'url')
                $params[$key] = urldecode($val);
        }
        $params += $_POST;
        $this->params = $params;
    }

    private function load_ref() {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "")
            $this->ref = $_SERVER['HTTP_REFERER'];
        else
            $this->ref = $this->root_url;
    }
}
