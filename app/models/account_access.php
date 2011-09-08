<?php

class AccountAccess extends BaseModel {
    static $dbname = 'login';
    static $table = 'account_access';
    static $fields = array('id', 'gmlevel', 'realmid');
    static $per_page = 1000;
    
    function get_account(){
        return Account::find($this->id);
    }
}
