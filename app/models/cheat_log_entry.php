<?php

class CheatLogEntry extends BaseModel {
    static $table = 'anticheat_log';
    static $fields = array('guid', 'checktype', 'map', 'zone', 'alarm_time', 'charname', 'lastspell');
    static $relations = array(
        'CheatConfigEntry' => array(
            'type' => 'has_one',
            'field' => 'checktype',
            'fk' => 'checktype',
            
        )
    );
    
    function get_character(){
        return $this->realm->find_characters($this->guid);
    }
    
    function get_account(){
        $accid = $this->character->account;
        if(is_numeric($accid)){
            return Account::find($accid);
        } else {
            return null;
        }
    }
    
    function get_type(){
        return $this->realm->find_cheat_config_entry($this->checktype);
    }
}
