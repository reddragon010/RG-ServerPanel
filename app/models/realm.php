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
    
    public static function find_all_available(){
        $databases = Config::instance('databases')->get_value(Environment::$name);
        $available_realm_ids = array_keys($databases['realm']);
        return self::find()->where(array('id IN (' . join(',',$available_realm_ids) . ')' ))->all();
    }
    
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
        return AccountAccess::find()->where(array('realmid = :realmid OR realmid = -1', 'realmid' => $this->id))->all();
    }
}
