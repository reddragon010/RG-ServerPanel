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
            $guilds += $realm->find_guilds('all', array('conditions' => $params));
            $guilds_count += $realm->find_guilds_count(array('conditions' => $params));
        }
        
        $this->render(array(
            'guilds' => $guilds,
            'guilds_count' => $guilds_count
        ));
    }
    
    function show($params){
        $realm = Realm::find($params['rid']);
        $guild = $realm->find_guilds('first',array('conditions' => array('guildid = ?',$params['id'])));
        $this->render(array('guild' => $guild));
    }
}