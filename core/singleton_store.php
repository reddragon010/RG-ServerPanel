<?php
abstract class SingletonStore {
    static private $instances = array();
    
    public static function instance($name){
        $class = get_called_class();
        if(!isset(self::$instances[$class])){
            self::$instances[$class] = array();
        }
        if(!isset(self::$instances[$class][$name])){
            self::$instances[$class][$name] = new Template($name);
        }
        return self::$instances[$class][$name];
    }
    
    protected function __construct($name) {}
}
