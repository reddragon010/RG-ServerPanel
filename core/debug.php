<?php

class Debug {

    private static $engine;
    private static $loaded = false;

    static function setup() {
        if (!self::$loaded) {
            self::load();
            self::$loaded = true;
        }
        if (!isset(self::$engine)) {
            $opts = Environment::get_config_value('phpdebug');
            self::$engine = new PHP_Debug($opts);
        }
    }

    private function __construct() {
        
    }

    static private function load() {
        PHP_Debug_Autoloader::register();
        set_include_path(FRAMEWORK_ROOT . '/lib/PHP_Debug/' . PATH_SEPARATOR . get_include_path());
    }

    static function _query($sql, $values) {
        foreach ($values as $key=>$value) {
            $sql = str_replace($key,$value,$sql);
        }
        //self::$engine->dump($values);
        return self::$engine->query($sql);
    }

    public static function __callStatic($name, $parameters) {
        if (!self::$loaded) {
            return false;
        } else if (method_exists('Debug', '_' . $name)) {
            return call_user_func_array(array('Debug', '_' . $name), $parameters);
        } else if (method_exists(self::$engine, $name)) {
            return call_user_func_array(array(self::$engine, $name), $parameters);
        }
    }

}
