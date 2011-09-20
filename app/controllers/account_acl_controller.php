<?php

class AccountAclController extends BaseController {
    function index(){
        $realms = Realm::find()->all();
        $this->render(array(
            'realms' => $realms
            ));
    }
}
