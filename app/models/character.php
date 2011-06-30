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
        $result = parent::find($type,$options, Environment::get_database('realm' . $realm->id));
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
        return parent::count($options, Environment::get_database('realm' . $realm->id));
    }

    public function after_build() {
        if (!empty($this->account))
            $this->accountobj = Account::find($this->account);
    }

}