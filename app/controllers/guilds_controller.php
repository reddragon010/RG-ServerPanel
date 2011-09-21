<?php

class GuildsController extends BaseController {
    var $before = array(
        'check_login'
    );
    
    function index($params=array()) {
        $realms = Realm::find()->all();
        $guilds = array();
        $guilds_count = 0;
        foreach($realms as $realm){
            $find = Guild::find()
                    ->where($params)
                    ->realm($realm->id)
                    ->page($params['page']);
            $guilds += $find->all();
            $guilds_count += $find->count();
        }
        
        $this->render(array(
            'guilds' => $guilds,
            'guilds_count' => $guilds_count
        ));
    }
    
    function show($params){
        $guild = Guild::find()->where(array('guildid'=> $params['id']))->realm($params['rid'])->first();
        $this->render(array('guild' => $guild));
    }
}