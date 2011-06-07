<?php

/**
 * 
 */
class home_controller extends application_controller {

    var $before = array(
        'check_login'
    );

    function index() {
        $this->render();
    }

}
