<?php

abstract class Singleton {
    private static $instances = array();
    
    public static function instance(){
        $class = get_called_class();
        if(!isset(self::$instances[$class])){
            self::$instances[$class] = new $class();
        }
        return self::$instances[$class];
    }
    
    protected function __construct(){}
}
