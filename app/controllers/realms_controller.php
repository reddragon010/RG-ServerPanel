<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class realms_controller extends application_controller {

    var $before = array(
        'check_login'
    );

    function index() {
        $realms = Realm::find('all');
        $this->render(array('realms' => $realms));
    }

}
