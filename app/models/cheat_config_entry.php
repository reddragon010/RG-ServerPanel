<?php

class CheatConfigEntry extends BaseModel {
    static $dbname = 'realm';
    static $table = 'anticheat_config';
    static $fields = array('checktype', 'description');
    static $primary_key = 'checktype';
    
    public static function find($type, $options = array(), $additions = array()) {
        self::set_dbid($options['conditions']['realm_id']);
        $additions['realm'] = Realm::find($options['conditions']['realm_id']);
        unset($options['conditions']['realm_id']);
        return parent::find($type, $options, $additions);
    }
    
    public static function count($options = array()) {
        self::set_dbid($options['conditions']['realm_id']);
        unset($options['conditions']['realm_id']);
        return parent::count($options);
    }
}