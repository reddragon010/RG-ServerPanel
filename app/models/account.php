<?php

class Account extends BaseModel {
    static $dbname = 'login';
    static $table = 'account';
    static $primary_key = 'id';
    static $name_field = 'username';
    static $plural = 'accounts';
    static $fields = array('id', 'username', 'email', 'expansion', 'joindate', 'last_ip', 'locked');
    
    public function before_save() {
        $this->sha_pass_hash = $this->hash_password($this->username, $this->password);
        return true;
    }
    
    //---------------------------------------------------------------------------
    //-- Validations
    //---------------------------------------------------------------------------
    public function validate() {
        if (!isset($this->username)) {
            $this->errors[] = "Username is not defined!";
        } else {
            if (User::find('first', array('conditions' => array("username = $this->username")))) {
                $this->errors[] = "The Username is already in use, Please try another Username";
            }
        }

        if (!isset($this->password)) {
            $this->errors[] = "Please enter a Password";
        } elseif (!$this->confirm) {
            $this->errors[] = "Please Confirm the Password";
        } elseif (($this->password && $this->confirm) && ($this->password != $this->confirm)) {
            $this->errors[] = "Passwords do not match!";
        }

        if (!isset($this->email)) {
            $this->errors[] = "Please Enter your Email";
        } else {
            if (User::find('first', array('conditions' => array("email = $this->email")))) {
                $this->errors[] = "That Email is Already in Use. Please try Another one";
            }
        }

        return empty($this->errors);
    }
    
    //---------------------------------------------------------------------------
    //-- Relations
    //---------------------------------------------------------------------------
    function get_characters() {
        $realms = Realm::find('all');

        $characters = array();
        foreach ($realms as $realm) {
            $characters += $realm->find_characters('all', array('conditions' => array('account = ?', $this->id)));
        }
        return $characters;
    }
    
    function get_accounts_with_same_ip(){
        $accounts = Account::find('all', array('conditions' => array('last_ip' => $this->last_ip)));
        return $accounts;
    }
    
    function get_bans(){
        $bans = AccountBan::find('all', array('conditions' => array('id = ?', $this->id)));
        return $bans;
    }
    
    function get_access_levels(){
        $access_levels = AccountAccess::find('all', array('conditions' => array('id = ?', $this->id)));
        return $access_levels;
    }
    
    function get_access_realms(){
        $levels = $this->get_access_levels();
        $realms = array();
        foreach($levels as $level){
            if($level->gmlevel >= Environment::get_config_value('min_gm_level','access')){
                if($level->realmid == -1){
                    $realms = Realm::find('all');
                    break;
                } else {
                    $realms = Realms::find('first',array('conditions' => array('realmid' => $level->realmid)));
                }
            } 
        }
        return $realms;
    }
    
    function get_lowest_gm_level(){
        $access_level = AccountAccess::find('first', array(
            'conditions' => array('id' => $this->id),
            'order' => 'gmlevel ASC'
            ));
        return $access_level->gmlevel;
    }
    
    function get_highest_gm_level(){
        $access_level = AccountAccess::find('first', array(
            'order' => 'gmlevel DESC',
            'conditions' => array('id' => $this->id)
            ));
        return $access_level->gmlevel;
    }
    
    //---------------------------------------------------------------------------
    //-- Virtual Attributes
    //---------------------------------------------------------------------------
    public function get_banned() {
        $ban = AccountBan::find('first', array('conditions' => array('id = ? AND active = 1', $this->id)));
        if($ban){
            return true;
        } else {
            return false;
        }
    }
    
    public function get_online() {
        $realms = Realm::find('all');

        $online = false;
        foreach ($realms as $realm) {
            $online_char = $realm->find_characters('first', array('conditions' => array('online = 1')));
            if (!empty($online_char)) {
                $online = true;
                break;
            }
        }
        return $online;
    }
}
