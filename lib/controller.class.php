<?php

/**
 * 
 */
class Controller {

    function __construct() {
        
    }

    public function render($data=array()) {
        global $request;
        $tpl = Template::getInstance(static::name());
        $tpl->render($request['action'], $data);
    }

    public function render_ajax($status, $msg="") {
        $return['status'] = $status;
        $return['msg'] = $msg;
        echo json_encode($return);
    }

    public static function name() {
        $class = get_called_class();
        $exploded_class_name = explode('_', $class);
        return $exploded_class_name[0];
    }

    function redirect_to($arrayOrUrl="",$params=array()) {
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
    
    function redirect_back(){
        global $request;
        $this->redirect_to($request['ref']);
    }

    function flash($type, $message, $hops=0) {
        $_SESSION['flash'] = array();
        $_SESSION['flash']['msg'] = $message;
        $_SESSION['flash']['type'] = $type;
        $_SESSION['flash']['hops'] = $hops;
    }
    
    function paramsToUrlString($params){
        $temp = array();
        foreach($params as $key=>$val){
            $temp[] = "$key=$val";
        }
        return '?' . join('&', $temp);
    }

}
