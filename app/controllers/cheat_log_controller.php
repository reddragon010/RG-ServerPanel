<?php

class CheatLogController extends BaseController {
    function index($params){     
        if(empty($params['realm_id']))
            $params['realm_id'] = Realm::find('first')->id;
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
        
        $cheatconfig = CheatConfigEntry::find('all',array('conditions' => array('realm_id' => $params['realm_id'])));
        $reasons = array('' => 'all');
        foreach($cheatconfig as $cc){
            $reasons[(string)$cc->checktype] = $cc->description;
        }
        
        $this->render(array(
            'log_entries' => CheatLogEntry::find('all',array('conditions' => $params, 'order' => $params['order'])),
            'log_entries_count' => CheatLogEntry::count(array('conditions' => $params)),
            'realmnames' => $realmnames,
            'realmid' => $realm->id,
            'reasons' => $reasons
        ));
    }
}
