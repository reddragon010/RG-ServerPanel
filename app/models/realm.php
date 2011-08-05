<?php

class Realm extends BaseModel {

    var $online = NULL;
    var $uptime = NULL;
    static $dbname = 'login';
    static $table = 'realmlist';
    static $fields = array(
      'id',
      'name',
      'address',
      'port',
      'icon',
      'allowedsecuritylevel',
      'gamebuild'
    );

    function get_status($force=false) {
        if ($this->online == NULL || $force) {
            if (!$sock = @fsockopen($this->address, $this->port, $num, $error, 3))
                $this->online = true;
            else {
                $this->online = false;
                fclose($sock);
            }
        }
        return $this->online;
    }
    
    function get_acl(){
        return AccountAccess::find('all', array('conditions' => array('realmid = :realmid OR realmid = -1', 'realmid' => $this->id)));
    }

    public function find_characters($type, $options=array()) {
        return Character::find($type, $options, $this);
    }

    public function find_characters_count($options) {
        return Character::count($options, $this);
    }
    
    public function find_guilds($type, $options=array()) {
        return Guild::find($type, $options, $this);
    }

    public function find_guilds_count($options) {
        return Guild::count($options, $this);
    }

}
