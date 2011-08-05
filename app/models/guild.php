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
    
    public function get_leader(){
        return $this->realm->find_characters('first', array('conditions' => array('guid' => $this->leaderguid)));
    }
    
}
