<?php
namespace Controller;

class Home extends \Core\BasicController {

    var $before = array(
        'check_login'
    );

    function index() {
        $this->render();
    }

}
