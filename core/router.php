<?php

class Router {
    public static $default = array(
        'controller' => 'home', 
        'action' => 'index'
    );

    public static $controller;
    private static $app_controller;
    public static $action;
    public static $params;
    
    public static function init() {
        self::$app_controller = new ApplicationController();
        self::set_controller();
        self::set_action();
        self::$params = Request::$params;
        Debug::add('Init Route to ' . get_class(self::$controller) . '->' . self::$action . ' with ' . var_export(self::$params,true));
    }
    
    private static function set_controller(){
        if(Request::$controller == ''){
            $controller = self::$default['controller'] . 'Controller';
        } else {
            $controller = Toolbox::to_camel_case(Request::$controller, true) . 'Controller';
        }
        if (class_exists($controller)) {
            self::$controller = new $controller();
        } else {
            self::$controller = self::app_controller;
            Request::$action = 'error';
            Request::$params['status'] = '404';
        }
    }
    
    private static function set_action(){
        if(Request::$action == ''){
            $action = self::$default['action'];
        } else {
            $action = Request::$action;
        }
        self::$action = $action;
    }
    
    public static function route(){
        if(
            self::call_array_on_class(self::$app_controller, 'before_all') &&
            self::call_array_on_class(self::$app_controller, 'before')
        ){
            Debug::add("Router calling ".get_class(self::$controller)."->{self::action}() with " . var_export(self::$params, true));
            call_user_func_array(array(self::$controller, self::$action), array(self::$params));
        }else{
            Debug::add("Router halted");
        }
    }
    
    private static function call_array_on_class($class, $array){
        if (isset($class->$array) && !empty($class->$array)) {
            foreach ($class->$array as $call) {
                if(!$class->$call()){
                    Debug::add("Router failed on ".get_class($class)."->$call())");
                    return false;
                }
            }
        }
        return true;
    }
}
