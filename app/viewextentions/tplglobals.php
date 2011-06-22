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
        return Environment::$app_url;
    }

    function themeurl() {
        return Environment::$app_theme_url;
    }

    function request() {
        global $request;
        return $request;
    }

}