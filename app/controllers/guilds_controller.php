<?php

class GuildsController extends BaseController {
    var $before = array(
        'check_login'
    );
    
    function index($params=array()) {
        $realms = Realm::find('all');
        $guilds = array();
        
        foreach($realms as $realm){
            $guilds += $realm->find_guilds('all');
        }

        $tpl_data = array(
            'guilds' => $guilds,
        );
        $this->render($tpl_data);
    }
}