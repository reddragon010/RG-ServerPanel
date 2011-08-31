<?php
class Guild extends BaseModel {
    
    static $table = 'guild';
    static $primary_key = 'guildid';
    static $name_field = 'name';
    static $plural = 'guilds';
    static $fields = array(
        'guildid',
        'name',
        'leaderguid',
        'info',
        'motd',
        'createdate',
        'BankMoney'
    );
    
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
    
    public function get_leader(){
        return Character::find('first', array('conditions' => array('guid' => $this->leaderguid, 'realm_id' => $this->realm->id)));
    }
    
}
