<?php

class Realm extends BaseModel {

    var $online = NULL;
    var $uptime = NULL;
    static $dbname = 'login';
    static $table = 'realmlist';

    function get_status($force=false) {
        if ($this->online == NULL || $force) {
            if (!$sock = @fsockopen($this->ip, $this->port, $num, $error, 3))
                $this->online = true;
            else {
                $this->online = false;
                fclose($sock);
            }
        }
        return $this->online;
    }

    public function find_characters($type, $options=array()) {
        return Character::find($type, $options, $this);
    }

    public function find_characters_count($options) {
        global $dbs;
        return Character::count($options, $this);
    }

}
