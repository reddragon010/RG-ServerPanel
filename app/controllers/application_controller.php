<?php
class ApplicationController extends BaseController {

    var $before_all = array('load_user','check_permission');

    function load_user() {
        return User::load_current_user();
    }

    function check_login() {
        $current_user = User::$current;
        if (!isset($current_user) || empty($current_user)) {
            $this->flash('error', 'you are not logged in or your session timed out - please relog');
            $this->redirect_to_login();
            return false;
        }
        return true;
    }
    
    function check_permission(){
        $controller = get_class(Router::$controller);
        $action = Router::$action;
        if(isset(User::$current)){
            $allowed = User::$current->is_permitted_to($action, $controller); 
        } else {
            $allowed = Permissions::check_permission($controller, $action);
        }
        
        if(!$allowed){
            if(isset(User::$current)){
                $this->error(array('status' => '401'));
            } else {
                $this->flash('error', 'Please LogIn');
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
