<?php

class application_controller extends Controller {

    var $before_all = array('load_user');

    function load_user() {
        global $user;
        if (!isset($user) && !empty($_SESSION['userid'])) {
            if (!empty($_SESSION['userdata'])) {
                $user = User::build($_SESSION['userdata']);
            } else {
                $user = User::find($_SESSION['userid']);
                $_SESSION['userdata'] = $user->data;
            }
        }
    }

    function check_login() {
        if (!isset($user) || empty($user) || empty($_SESSION['userid'])) {
            $this->redirect_to('session', 'add');
        }
    }

}
