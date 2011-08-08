<?php
abstract class SingletonStore {
    static private $instances = array();
    
    public static function instance($name){
        $class = get_called_class();
        if(!isset(self::$instances[$class])){
            self::$instances[$class] = array();
        }
        if(!isset(self::$instances[$class][$name])){
            self::$instances[$class][$name] = new $class();
            call_user_func(array(self::$instances[$class][$name], 'init'), $name);
        }
        return self::$instances[$class][$name];
    }
    
    final protected function __construct() {}
    
    abstract protected function init($name);
}
