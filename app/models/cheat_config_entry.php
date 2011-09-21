<?php

class CheatConfigEntry extends BaseModel {
    static $dbname = 'realm';
    static $dbid = null;
    static $table = 'anticheat_config';
    static $fields = array('checktype', 'description');
    static $primary_key = 'checktype';
    
    public function scope_realm($find, $realm_id){
        $find->dbid = $realm_id;
        $find->additions(array('realm' => Realm::find($realm_id)));
        return $find;
    }
    
}