<?php
class HomeController extends BaseController {

    var $before = array(
        'check_login'
    );

    function index() {
        $this->render();
    }

}
