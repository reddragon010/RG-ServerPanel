<?php
class BaseController {

    function __construct() {
        
    }
    
    function __call($name, $arguments) {
        $app_controller = new ApplicationController();
        if(method_exists($app_controller, $name)){
            call_user_func_array(array($app_controller, $name), $arguments);
        }
    }
    
    function __get($name){
        $app_controller = new ApplicationController();
        if(isset($app_controller->$name)){
            return $app_controller->$name;
        }
    }
    
    public function render($data=array(),$type='html') {
        switch($type){
            case 'html':
                $this->render_html($data);
                break;
            case 'json':
                $this->render_json($data);
                break;
        }
        
    }
    
    public function render_partial($partial, $data=array()){
        $tpl = Template::instance(static::get_name());
        $tpl->render($partial, $data);
    }
    
    public function render_json($data=array()){
        $data = array_map(function($elem){return strip_tags($elem);}, $data);
        echo json_encode($data);
    }
    
    public function render_html($data=array()){
        $tpl = Template::instance(static::get_name());
        $tpl->render(Router::$action, $data);
    }

    public function render_ajax($status, $msg="", $data=array()) {
        if(!empty($data)){
            $return['data'] = $data;
        }
        $return['status'] = $status;
        $return['msg'] = $msg;
        $this->render_json($return);
    }
    
    public function render_error($status){
        $this->set_header_status($status);
        if(class_exists('ApplicationController')){
            $controller = new ApplicationController();
            $controller->render($status);
        } else {
            echo $status;
        }
    }

    public static function get_name() {
        $class = get_called_class();
        $name = str_replace('Controller', '', $class);
        return strtolower($name);
    }

    public function redirect_to($arrayOrUrl="",$params=array()) {    
        if(is_array($arrayOrUrl)){
            if(Environment::get_value('clean_urls') == true){
                $url = "/{$arrayOrUrl[0]}/{$arrayOrUrl[1]}";
            } else {
                $url = 'index.php';
                $params['url'] = "/{$arrayOrUrl[0]}/{$arrayOrUrl[1]}";
            }
        } elseif($arrayOrUrl=="") {
            $url = Request::$base_url;
        } else {
            $url = $arrayOrUrl;
        }
        if(!empty($params)){
            $url .= $this->paramsToUrlString($params);
        }
        header("Location: $url");
    }
    
    public function set_header_status($status){
        switch($status){
            case 404:
                $header = "HTTP/1.0 404 File Not Found";
                break;
            default:
                $header = "";
        }
        header($header);
    }
    
    public function redirect_back(){
        $this->redirect_to(Request::$ref);
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
