<?php

class AccountPartner extends BaseModel {
    static $dbname = 'web';
    static $table = 'account_partners';
    static $fields = array('id', 'account_id', 'partner_id', 'comment', 'until');
    
    public $account = 'test';
    
    public function get_account(){
        return Account::find($this->account_id);
    }
    
    public function get_partner(){
        return Account::find($this->partner_id);
    }
    
    public function validate() {
        if (!isset($this->account_id) || $this->account_id == '') {
            $this->errors[] = "Account is not defined!";
            return false;
        }
        if (!isset($this->partner_id) || $this->partner_id == '') {
            $this->errors[] = "Partner is not defined!";
            return false;
        }
        
        if($this->new && isset($this->until) && $this->until < time()){
            $this->errors[] = "Until-Date can't be in the past";
            return false;
        }
        
        $double_check = AccountPartner::find('first',array('conditions' => array('account_id' => $this->account_id, 'partner_id' => $this->partner_id)));
        if($double_check && (!isset($double_check->until) || $double_check->until > time()) ){
            $this->errors[] = "Account-Partner Relation already exists";
            return false;
        }

        return true;
    }
}

?>
