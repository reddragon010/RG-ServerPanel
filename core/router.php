<?php

class Router extends Singleton {
    public static $default = array(
        'controller' => 'home', 
        'action' => 'index'
    );

    private $request;
    public $controller;
    private $app_controller;
    public $action;
    public $params;
    
    protected function __construct() {
        $this->request = Request::instance();
        $this->app_controller = new ApplicationController();
        $this->set_controller();
        $this->set_action();
        $this->params = $this->request->params;
    }
    
    private function set_controller(){
        if($this->request->controller == ''){
            $controller = self::$default['controller'] . 'Controller';
        } else {
            $controller = ucfirst($this->request->controller) . 'Controller';
        }
        if (class_exists($controller)) {
            $this->controller = new $controller();
        } else {
            $this->app_controller->set_header_status(404);
            $this->app_controller->render("404");
            die();
        }
    }
    
    private function set_action(){
        if($this->request->action == ''){
            $action = self::$default['action'];
        } else {
            $action = $this->request->action;
        }
        $this->action = $action;
    }
    
    public function route(){
        $this->call_array_on_class($this->app_controller, 'before_all');
        $this->call_array_on_class($this->controller, 'before');

        call_user_func_array(array($this->controller, $this->action), array($this->params));
    }
    
    private function call_array_on_class($class, $array){
        if (isset($class->$array) && !empty($class->$array)) {
            foreach ($class->$array as $call) {
                $class->$call();
            }
        }
    }
}
