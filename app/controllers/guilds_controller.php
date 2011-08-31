<?php

class GuildsController extends BaseController {
    var $before = array(
        'check_login'
    );
    
    function index($params=array()) {
        $realms = Realm::find('all');
        $guilds = array();
        $guilds_count = 0;
        foreach($realms as $realm){
            $params['realm_id'] = $realm->id;
            $guilds += Guild::find('all', array('conditions' => $params));
            $guilds_count += Guild::count(array('conditions' => $params));
        }
        
        $this->render(array(
            'guilds' => $guilds,
            'guilds_count' => $guilds_count
        ));
    }
    
    function show($params){
        $guild = $realm->find_guilds('first',array('conditions' => array('guildid'=> $params['id'], 'realm_id' => $params['rid'])));
        $this->render(array('guild' => $guild));
    }
}