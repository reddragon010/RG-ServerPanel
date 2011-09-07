<?php

class Character extends BaseModel {
    static $dbname = 'realm';
    static $dbid = null;
    static $table = 'characters';
    static $primary_key = 'guid';
    static $name_field = 'name';
    static $plural = 'characters';
    static $fields = array(
        'guid',
        'name',
        'online',
        'map',
        'zone',
        'account',
        'race',
        'class',
        'gender',
        'level',
        'money',
        'totaltime',
        'deleteinfos_account',
        'deleteinfos_name',
        'deletedate'
    );
    
    static $relations = array(
        'accountobj' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'account',
            'conditions' => array('realm_id' => '#$this->realm->id')
        )
    );
    
    public static $per_page = 23;
    public $realm;
    
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
    
    public function get_deleted(){
        return  !empty($this->deleteinfos_name) && 
                !empty($this->deleteinfos_account) && 
                empty($this->name) && 
                $this->account_id == 0;
    }
    
    public function recover(){
        if($this->deleted){
            $this->name = $this->deleteinfos_name;
            $this->account = $this->deleteinfos_account;
            $this->deleteinfos_name = null;
            $this->deleteinfos_account = null;
            $this->deletedate = null;
            return $this->save();
        } else {
            $this->errors[] = "Char is not deleted";
            return false;
        }
    }
    
    public function validate(){
        if ($this->online){
            $this->errors[] = "Account is online! Please SaveBan and Kick it to avoid 'The End Of The WO-World'!";
            return false;
        }
        
        return true;
    }

}