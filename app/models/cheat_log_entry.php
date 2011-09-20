<?php

class CheatLogEntry extends BaseModel {
    static $dbname = 'realm';
    static $dbid = null;
    static $table = 'anticheat_log';
    static $fields = array('guid', 'checktype', 'map', 'zone', 'alarm_time', 'charname', 'lastspell');
    static $relations = array(
        'config' => array(
            'model' => 'CheatConfigEntry',
            'type' => 'has_one',
            'field' => 'checktype',
            'fk' => 'checktype',
            'scopes' => array(
                'realm' => array()
            )
        ),
        'character' => array(
            'model' => 'Character',
            'type' => 'has_one',
            'field' => 'guid',
            'fk' => 'guid',
            'scopes' => array(
                'realm' => array()
            )
        )
    );
    public $realm;
    
    public function scope_realm($find, $realm_id){
        $find->dbid = $realm_id;
        $find->additions(array('realm' => Realm::find($realm_id)));
        return $find;
    }

}
