<?php
class ApplicationController extends BaseController {

    var $before_all = array('load_user');

    function load_user() {
        global $current_user;
        if (!isset($current_user) && !empty($_SESSION['userid'])) {
            $current_user = new User($_SESSION['userid']);
        }
    }

    function check_login() {
        global $current_user;
        if (!isset($current_user) || empty($current_user)) {
            $this->flash('error', 'you are not logged in or your session timed out - please relog');
            $this->redirect_to_login();
        } elseif(!$current_user->is_gm()){
            $current_user->logout();
            $this->flash('error', 'you have not enought rights to use this system');
            $this->redirect_to_login();
        }
    }
    
    function redirect_to_login(){
        $this->redirect_to(array('session', 'add'));
    }
    
    function render($view,$data=array()){
        $tpl = Template::instance("application");
        $tpl->render($view, $data);
    }
    
    function error($params){
        $this->set_header_status(404);
        $this->render($params['status']);
    }

}
