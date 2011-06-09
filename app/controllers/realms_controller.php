<?php
class RealmsController extends BaseController {

    var $before = array(
        'check_login'
    );

    function index() {
        $realms = Realm::find('all');
        $this->render(array('realms' => $realms));
    }

}
