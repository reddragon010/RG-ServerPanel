<?php

class Character extends BaseModel {

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
        'deleteInfos_Account',
        'deleteInfos_Name',
        'deleteDate'
    );
    public static $per_page = 23;
    public $accountobj;
    public $realm;
    
    public static function find($type, $options, $realm){
        parent::set_dbname('realm' . $realm->id);
        $result = parent::find($type,$options,array('realm' => $realm));
        return $result;
    }
    
    public static function count($options, $realm){
        parent::set_dbname('realm' . $realm->id);
        return parent::count($options);
    }

    public function after_build() {
        if (!empty($this->account))
            $this->accountobj = Account::find($this->account);
    }

}