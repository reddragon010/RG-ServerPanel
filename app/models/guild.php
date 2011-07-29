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
    
    public $leader;
    public $realm;
    
    public static function find($type, $options, $realm){
        self::set_dbname('realm' . $realm->id);
        $result = parent::find($type,$options,array('realm' => $realm));
        return $result;
    }
    
    public static function count($options, $realm){
        self::set_dbname('realm' . $realm->id);
        return parent::count($options);
    }
    
    public function get_leader(){
        return $this->realm->find_characters('first', array('conditions' => array('guid' => $this->leaderguid)));
    }
    
}
