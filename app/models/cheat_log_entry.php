<?php

class CheatLogEntry extends BaseModel {
    static $table = 'anticheat_log';
    static $fields = array('guid', 'checktype', 'map', 'zone', 'alarm_time', 'charname', 'lastspell');
    static $relations = array(
        'config' => array(
            'model' => 'CheatConfigEntry',
            'type' => 'has_one',
            'field' => 'checktype',
            'fk' => 'checktype',
            'conditions' => array('realm_id' => '#$this->realm->id')
        ),
        'character' => array(
            'model' => 'Character',
            'type' => 'has_one',
            'field' => 'guid',
            'fk' => 'guid',
            'conditions' => array('realm_id' => '#$this->realm->id')
        )
    );
    public $realm;
    
    public static function find($type, $options = array(), $additions = array()) {
        self::set_dbname('realm' . $options['conditions']['realm_id']);
        $additions['realm'] = Realm::find($options['conditions']['realm_id']);
        unset($options['conditions']['realm_id']);
        return parent::find($type, $options, $additions);
    }
    
    public static function count($options = array()) {
        self::set_dbname('realm' . $options['conditions']['realm_id']);
        unset($options['conditions']['realm_id']);
        return parent::count($options);
    }
    
    function get_type(){
        return $this->realm->find_cheat_config_entry($this->checktype);
    }
}
