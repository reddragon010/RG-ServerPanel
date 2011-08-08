<?php

abstract class Singleton {
    private static $instances = array();
    
    public static function instance(){
        $class = get_called_class();
        if(!isset(self::$instances[$class])){
            self::$instances[$class] = new $class();
            if (method_exists($class, 'init')) {
                if (func_num_args() > 0) {
                    $args = func_get_args();
                    call_user_func_array(array(self::$instances[$class], 'init'), $args);
                } else {
                    call_user_func(array(self::$instances[$class], 'init'));
                }
            }
        }
        return self::$instances[$class];
    }
    
    final protected function __construct(){}
}
