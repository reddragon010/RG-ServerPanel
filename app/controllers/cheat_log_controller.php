<?php

class CheatLogController extends BaseController {
    function index($params){     
        if(empty($params['realm_id']))
            $params['realm_id'] = Realm::find()->first()->id;
        if(!isset($params['order'])){
            $params['order'] = 'alarm_time DESC';
        }
        
        if(isset($params['checktype']) && $params['checktype'] == 'all')
            unset($params['checktype']);
        
        $realms = Realm::find()->all();
        $realmnames = array();
        foreach($realms as $r){
            $realmnames[$r->id] = $r->name;
        }
        
        $cheatconfig = CheatConfigEntry::find()->realm($params['realm_id'])->all();
        
        $reasons = array('' => 'all');
        foreach($cheatconfig as $cc){
            $reasons[(string)$cc->checktype] = $cc->description;
        }
        
        $log_entries = CheatLogEntry::find()
                ->where($params)
                ->realm($params['realm_id'])
                ->order($params['order']);
        
        $data = array(
            'log_entries' => $log_entries->all(),
            'log_entries_count' => $log_entries->count(),
            'realmnames' => $realmnames,
            'realmid' => $realm->id,
            'reasons' => $reasons
        );
        
        if(!isset($params['partial'])){
            $this->render($data);
        } else {
            $this->render_partial('cheatlog', $data);
        }
    }
}
