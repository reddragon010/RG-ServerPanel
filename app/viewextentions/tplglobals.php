<?php

class tplglobals {

    function current_user() {
        global $current_user;
        return $current_user;
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
}