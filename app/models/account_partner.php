<?php

class AccountPartner extends BaseModel {
    static $dbname = 'web';
    static $table = 'account_partners';
    static $fields = array('account_id', 'partner_id', 'comment');
    
    public $account = 'test';
    
    public function get_account(){
        return Account::find($this->account_id);
    }
    
    public function get_partner(){
        return Account::find($this->partner_id);
    }
}

?>
