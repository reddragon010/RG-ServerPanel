<?php
class ApplicationController extends BaseController {

    var $before_all = array('load_user','check_permission');

    function load_user() {
        global $current_user;
        if (!isset($current_user) && !empty($_SESSION['userid'])) {
            $current_user = new User($_SESSION['userid']);
            Debug::add('Loading current user ' . var_export($current_user,true));
        } else {
            Debug::add('unable to load user');
        }
        return true;
    }

    function check_login() {
        global $current_user;
        if (!isset($current_user) || empty($current_user)) {
            $this->flash('error', 'you are not logged in or your session timed out - please relog');
            $this->redirect_to_login();
            return false;
        }
        return true;
    }
    
    function check_permission(){
        global $current_user;
        if(isset($current_user)){
            $roleid = $current_user->get_roleid();
            $logged_in = true;
        } else {
            $roleid = 0;
            $logged_in = false;
        }
        
        $request = Request::instance();
        $controller = $request->controller;
        $action = $request->action;
        $allowed = Permissions::check_permission($roleid, $controller, $action);
        if(!$allowed){
            if($logged_in){
                $this->error(array('status' => '401'));
            } else {
                $this->redirect_to_login();
            }
            return false;
        }
        return true;
    }
    
    function redirect_to_login(){
        $this->redirect_to(array('session', 'add'));
    }
    
    function render($view,$data=array()){
        $tpl = Template::instance("application");
        $tpl->render($view, $data);
    }
    
    function error($params){
        $this->set_header_status($params['status']);
        $this->render($params['status']);
    }

}
