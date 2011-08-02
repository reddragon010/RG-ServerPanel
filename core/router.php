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
    
    protected function init() {
        $this->request = Request::instance();
        $this->app_controller = new ApplicationController();
        $this->set_controller();
        $this->set_action();
        $this->params = $this->request->params;
        Debug::add('Init Route to ' . get_class($this->controller) . '->' . $this->action . ' with ' . var_export($this->params,true));
    }
    
    private function set_controller(){
        if($this->request->controller == ''){
            $controller = self::$default['controller'] . 'Controller';
        } else {
            $controller = Toolbox::to_camel_case($this->request->controller, true) . 'Controller';
        }
        if (class_exists($controller)) {
            $this->controller = new $controller();
        } else {
            $this->controller = $this->app_controller;
            $this->request->action = 'error';
            $this->request->params['status'] = '404';
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
