<?php

class CheatLogController extends BaseController {
    function index($params){
        if(!empty($params['realm']) && is_numeric($params['realm'])){
            $realm = Realm::find($params['realm']);
        } else {
            $realm = Realm::find('first');
        }
        
        if(!isset($params['order'])){
            $params['order'] = 'alarm_time DESC';
        }
        
        if(isset($params['checktype']) && $params['checktype'] == 'all')
            unset($params['checktype']);
        
        $realms = Realm::find('all');
        $realmnames = array();
        foreach($realms as $r){
            $realmnames[$r->id] = $r->name;
        }
        
        $cheatconfig = $realm->find_cheat_config_entry('all');
        $reasons = array('' => 'all');
        foreach($cheatconfig as $cc){
            $reasons[(string)$cc->checktype] = $cc->description;
        }
        
        $this->render(array(
            'log_entries' => $realm->find_cheat_log_entry('all',array('conditions' => $params, 'order' => $params['order'])),
            'log_entries_count' => $realm->count_cheat_log_entry(array('conditions' => $params)),
            'realmnames' => $realmnames,
            'realmid' => $realm->id,
            'reasons' => $reasons
        ));
    }
}
