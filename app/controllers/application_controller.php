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
            $this->flash('error', 'you have not enought rights to use this system');
            $current_user->logout();
            $this->redirect_to_login();
        }
    }
    
    function redirect_to_login(){
        $this->redirect_to(array('session', 'add'));
    }
    
    function render($view,$data=array()){
        $tpl = Template::getInstance("application");
        $tpl->render($view, $data);
    }

}
