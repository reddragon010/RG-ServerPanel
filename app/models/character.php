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
        'totaltime'
    );
    public static $per_page = 23;
    public $accountobj;
    public $realm;
    
    public static function find($type, $options, $realm){
        parent::set_dbname('realm' . $realm->id);
        $result = parent::find($type,$options);
        if(is_array($result)){
            $op =  array_map(function($elem) use ($realm){
                $elem->realm = $realm;
                return $elem;
            }, $result);
        } elseif(is_object($result)){
            $result->realm = $realm;
            $op = $result; 
        } else {
            $op = false;
        }
        return $op;
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