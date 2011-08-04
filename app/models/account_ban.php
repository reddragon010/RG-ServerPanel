<?php
class AccountBan extends BaseModel {
    static $dbname = 'login';
    static $table = 'account_banned';
    static $fields = array('id', 'bandate', 'unbandate', 'bannedby', 'banreason', 'active');
    
    public function get_banning_account(){
        return Account::find('first', array('conditions' => array('id' => $this->id)));
    }
    
    public function validate() {
        if (!isset($this->id)) {
            $this->errors[] = "Account is not defined!";
            return false;
        }
        if (!isset($this->bandate)) {
            $this->errors[] = "Ban-Date is not defined!";
            return false;
        }
        if (!isset($this->bannedby)) {
            $this->errors[] = "Banning Account is not defined!";
            return false;
        }
        if ($this->unbandate < time() && $this->unbandate != 0){
            $this->errors[] = "Unbandate is in the past";
            return false;
        }
        $banned_check = AccountBan::find('first',array('conditions' => array('id' => $this->id, 'active' => '1')));
        if($this->active == 1 && $banned_check){
            $this->errors[] = "Account already banned!";
            return false;
        } elseif( $this->active == 0 && !$banned_check) {
            $this->errors[] = "Account is not banned!";
            return false;
        }
        
        return true;
    }
}
