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
    
    public function render($data=array()) {
        global $request;
        $tpl = Template::instance(static::get_name());
        $tpl->render(Router::instance()->action, $data);
    }

    public function render_ajax($status, $msg="") {
        $return['status'] = $status;
        $return['msg'] = $msg;
        echo json_encode($return);
    }

    public static function get_name() {
        $class = get_called_class();
        $name = str_replace('Controller', '', $class);
        return strtolower($name);
    }

    public function redirect_to($arrayOrUrl="",$params=array()) {
        if(is_array($arrayOrUrl)){
            if(Environment::get_config_value('clean_urls') == true){
                $url = "/{$arrayOrUrl[0]}/{$arrayOrUrl[1]}";
            } else {
                $url = 'index.php';
                $params['url'] = "/{$arrayOrUrl[0]}/{$arrayOrUrl[1]}";
            }
        } elseif($arrayOrUrl=="") {
            $url = Request::instance()->base_url;
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
