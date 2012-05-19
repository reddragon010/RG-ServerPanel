<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of StupidPrlf.
 *
 *    StupidPrlf is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    StupidPrlf is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with StupidPrlf.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Dreamblaze\Framework\Core;

class Controller {
    protected $params = array();

    function __construct() {

    }

    public function execute($action, $params){
        Logger::debug('Executing ' . get_class($this) . ' with ' . $action);
        if(method_exists($this, $action)){
            $this->params = $params;
            $this->exec_before_actions();
            Logger::debug($params);
            $this->$action($params);
            $this->exec_after_actions();
        }
        else
            throw new \Exception("Controller-Action $action not supported by " . get_class($this));
    }
    
    protected function render($data=array(),$type='html') {
        switch($type){
            case 'html':
                $this->render_html($data);
                break;
            case 'json':
                $this->render_json($data);
                break;
        }
        
    }

    protected function render_partial($partial, $data=array()){
        $tpl = Template::instance(static::get_name());
        $tpl->render($partial, $data);
    }

    protected function render_json($data=array()){
        $data = array_map(function($elem){
            if(is_string($elem)){
                return strip_tags($elem);
            } else {
                return $elem;
            }
        }, $data);
        echo json_encode($data);
    }

    protected function render_html($data=array()){
        $tpl = Template::instance(static::get_name());
        $tpl->render($this->params['action'], $data);
    }

    protected function render_ajax($status, $msg="", $data=array()) {
        if(!empty($data)){
            $return['data'] = $data;
        }
        $return['status'] = $status;
        $return['msg'] = $msg;
        $this->render_json($return);
    }

    protected function render_error($status){
        Logger::warning("Rendered HTTP-Error $status to " . $_SERVER['REMOTE_ADDR']);
        $this->set_header_status($status);
        $tpl = Template::instance("application");
        $tpl->render($status);
    }

    public static function get_name() {
        $class = get_called_class();
        $name = str_replace('Controller', '', $class);
        return strtolower($name);
    }

    protected function redirect_to($arrayOrUrl="",$params=array()) {
        if(is_array($arrayOrUrl)){
            if(Config::instance('framework')->get_value('clean_urls') == true){
                $url = "/{$arrayOrUrl[0]}/{$arrayOrUrl[1]}";
            } else {
                $url = 'index.php';
                $params['url'] = "/{$arrayOrUrl[0]}/{$arrayOrUrl[1]}";
            }
        } elseif($arrayOrUrl=="") {
            $url = Kernel::$request->base_url;
        } else {
            $url = $arrayOrUrl;
        }
        if(!empty($params)){
            $url .= $this->paramsToUrlString($params);
        }
        Logger::debug("Redirect to " . $url);
        if($url != Kernel::$request->relative_url)
            header("Location: $url");
        else
            $this->render_error(500);
    }

    protected function redirect_back(){
        $this->redirect_to(Kernel::$request->ref);
    }

    protected function flash($type, $message, $hops=0) {
        $_SESSION['flash'] = array();
        $_SESSION['flash']['msg'] = $message;
        $_SESSION['flash']['type'] = $type;
        $_SESSION['flash']['hops'] = $hops;
    }

    private function set_header_status($status){
        switch($status){
            case 404:
                $header = "HTTP/1.0 404 File Not Found";
                break;
            default:
                $header = "";
        }
        header($header);
    }

    private function paramsToUrlString($params){
        $temp = array();
        foreach($params as $key=>$val){
            $temp[] = "$key=$val";
        }
        return '?' . join('&', $temp);
    }

    private function exec_before_actions(){
        $this->call_array_on_class($this, 'before_all');
        $this->call_array_on_class($this, 'before');
    }

    private function exec_after_actions(){
        $this->call_array_on_class($this, 'after_all');
    }

    private function call_array_on_class($class, $array){
        if (isset($class->$array) && !empty($class->$array)) {
            foreach ($class->$array as $call) {
                if(!$class->$call()){
                    Logger::debug("Router exec on ".get_class($class)."->$call())");
                    throw new \Exception("Router failed on ".get_class($class)."->$call())");
                }
            }
        }
    }
}
