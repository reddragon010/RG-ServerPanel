<?php
namespace Core;

class BasicController {

    function __construct() {
        
    }
    
    function __call($name, $arguments) {
        $app_controller = new \Controller\Application();
        if(method_exists($app_controller, $name)){
            call_user_method_array($name, $app_controller, $arguments);
        }
    }
    
    function __get($name){
        $app_controller = new \Controller\Application();
        if(isset($app_controller->$name)){
            return $app_controller->name;
        }
    }
    
    public function render($data=array()) {
        global $request;
        $tpl = Template::getInstance(static::get_name());
        $tpl->render($request['action'], $data);
    }

    public function render_ajax($status, $msg="") {
        $return['status'] = $status;
        $return['msg'] = $msg;
        echo json_encode($return);
    }

    public static function get_name() {
        $class = get_called_class();
        $pos = strrpos($class, "\\");
        $name = substr($class, $pos+1);
        return $name;
    }

    public function redirect_to($arrayOrUrl="",$params=array()) {
        if(is_array($arrayOrUrl)){
            $url = "/{$arrayOrUrl[0]}/{$arrayOrUrl[1]}";
        } elseif($arrayOrUrl=="") {
            $url = Environment::$app_url;
        } else {
            $url = $arrayOrUrl;
        }
        if(!empty($params)){
            $url .= $this->paramsToUrlString(params);
        }
        header("Location: $url");
    }
    
    public function redirect_back(){
        global $request;
        $this->redirect_to($request['ref']);
    }

    public function flash($type, $message, $hops=0) {
        $_SESSION['flash'] = array();
        $_SESSION['flash']['msg'] = $message;
        $_SESSION['flash']['type'] = $type;
        $_SESSION['flash']['hops'] = $hops;
    }
    
    private function paramsToUrlString($params){
        $temp = array();
        foreach($params as $key=>$val){
            $temp[] = "$key=$val";
        }
        return '?' . join('&', $temp);
    }

}
