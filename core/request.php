<?php

class Request extends Singleton{
    public $controller;
    public $action;
    public $params = array();
    public $raw;
    public $ref;
    public $base_url;
    public $host;
    
    protected function init() {
        $this->parse_request();
        $this->set_controller();
        $this->set_action();
        $this->set_params();
        $this->set_ref();
        $this->set_base_url();
        $this->host = $_SERVER['SERVER_NAME'];
    }

    private function parse_request() {
        if(isset($_REQUEST['url'])){
           $url = $_REQUEST['url']; 
        } else {
           $url = '';
        }

        if (isset($url)) {
            if (substr($url, 0, 1) !== '/') {
                $url = '/' . $url;
            }
            $request = explode('/', $url);
        } else {
            $request = array();
        }
        $this->raw = $request;
    }

    private function set_controller() {
        if (empty($this->raw[1])) {
            $controller = '';
        } else {
            $controller = $this->raw[1];
        }
        $this->controller = $controller;
    }
    
    private function set_action() {
        if (empty($this->raw[2])) {
            $action = '';
        } else {
            $action = $this->raw[2];
        }
        $this->action = $action;
    }

    private function set_params() {
        $this->params = $_GET + $_POST;
    }

    private function set_ref() {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "") {
            $domain = parse_url($_SERVER['HTTP_REFERER']);
            if ($domain['host'] == $this->host) {
                $this->ref = $_SERVER['HTTP_REFERER'];
            } else {
                $this->ref = $this->base_url;
            }
        } else {
            $this->ref = $this->base_url;
        }
    }
    
    private function set_base_url() {
        try{
            $this->base_url = self::find_rooturl() . Environment::get_config_value('app_url_base');
        } catch(Exception $e) {
            $this->base_url = self::find_rooturl();
        }
    }

    private function find_rooturl() {
        $pageURL = 'http';

        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
            $pageURL .= "s";

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }

        return $pageURL;
    }

}
