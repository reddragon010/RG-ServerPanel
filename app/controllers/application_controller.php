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
            $this->redirect_to_login();
        }
    }
    
    function redirect_to_login(){
        $this->redirect_to(array('session', 'add'));
    }

}
