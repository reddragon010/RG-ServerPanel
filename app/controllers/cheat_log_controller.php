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
        
        $this->render(array(
            'log_entries' => $realm->find_cheat_log_entry('all',array('conditions' => $params, 'order' => $params['order'])),
            'log_entries_count' => $realm->count_cheat_log_entry(array('conditions' => $params)),
            'realmid' => $realm->id
        ));
    }
}
