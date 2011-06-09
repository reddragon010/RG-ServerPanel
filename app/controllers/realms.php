<?php
namespace Controller;

class Realms extends \Core\BasicController {

    var $before = array(
        'check_login'
    );

    function index() {
        $realms = Realm::find('all');
        $this->render(array('realms' => $realms));
    }

}
