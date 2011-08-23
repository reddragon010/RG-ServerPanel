<?php

class CheatLogController extends BaseController {
    function index($params){
        if(!empty($params['realm']) && is_numeric($params['realm'])){
            $realm = Realm::find($params['realm']);
        } else {
            $realm = Realm::find('first');
        }
        
        $this->render(array(
            'log_entries' => $realm->find_cheat_log_entry('all',array('conditions' => $params)),
            'log_entries_count' => $realm->count_cheat_log_entry(),
            'realmid' => $realm->id
        ));
    }
}
