<?php

class tplglobals {

    function current_user() {
        return User::$current;
    }

    function STATUS() {
        global $STATUS;
        return $STATUS;
    }

    function rooturl() {
        return Request::$base_url;
    }
    
    function params(){
        return Request::$params;
    }
    
    function pagetitle(){
        return Request::$controller . ' / ' . Request::$action;
    }
}