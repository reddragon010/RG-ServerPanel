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
    
    function show($params){
        $realm = Realm::find($params['rid']);
        $guild = $realm->find_guilds('first',array('conditions' => array('guildid = ?',$params['id'])));
        $this->render(array('guild' => $guild));
    }
}