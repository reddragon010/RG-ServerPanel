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
        'deleteinfos_account',
        'deleteinfos_name',
        'deletedate'
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