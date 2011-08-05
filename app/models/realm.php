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
        Character::set_dbname('realm' . $this->id);
        return Character::find($type, $options, array('realm' => $this));
    }

    public function count_characters($options) {
        Character::set_dbname('realm' . $this->id);
        return Character::count($options);
    }
    
    public function find_guilds($type, $options=array()) {
        Guild::set_dbname('realm' . $this->id);
        return Guild::find($type, $options, array('realm' => $this));
    }

    public function count_guilds($options) {
        Guild::set_dbname('realm' . $this->id);
        return Guild::count($options, $this);
    }

}
