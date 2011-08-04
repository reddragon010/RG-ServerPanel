<?php

class Account extends BaseModel {

    static $dbname = 'login';
    static $table = 'account';
    static $primary_key = 'id';
    static $name_field = 'username';
    static $plural = 'accounts';
    static $fields = array('id', 'username', 'sha_pass_hash' ,'email', 'expansion', 'joindate', 'last_login' ,'last_ip', 'locked');

    public function before_save() {
        if (isset($this->password)) {
            $this->sha_pass_hash = $this->hash_password($this->username, $this->password);
        }
        return true;
    }
    
    public $password = '';
    public $password_confirm = '';

    //---------------------------------------------------------------------------
    //-- Validations
    //---------------------------------------------------------------------------
    public function validate() {
        if (!isset($this->username)) {
            $this->errors[] = "Username is not defined!";
        } else {
            $account = Account::find('first', array('conditions' => array('username' => $this->username)));
            if ($account && $account->id != $this->id) {
                $this->errors[] = "The username is already in use, please try another one";
            }
        }

        if (!empty($this->password)) {
            if (empty($this->password_confirm)) {
                $this->errors[] = "Please enter the same password in the confirm-field";
            }elseif ($this->password != $this->password_confirm) {
                $this->errors[] = "Passwords do not match!";
            }
        }

        if (isset($this->email) && !empty($this->email)) {
            $account = Account::find('first', array('conditions' => array("email" => $this->email)));
            if ($account && $account->id != $this->id) {
                $this->errors[] = "That email is already in use. Please try another one";
            }
        } else {
            $this->errors[] = "Please enter an email-address";
        }

        return empty($this->errors);
    }

    //---------------------------------------------------------------------------
    //-- Relations
    //---------------------------------------------------------------------------
    function get_realms(){
        $realms = Realm::find('all');
        return $realms;
    }
    
    function get_characters() {
        $characters = array();
        foreach ($this->realms as $realm) {
            $result = $realm->find_characters('all', array('conditions' => array('account' => $this->id)));
            if(is_array($result))
                $characters += $result;
        }
        return $characters;
    }

    function get_accounts_with_same_ip() {
        $accounts = Account::find('all', array('conditions' => array('last_ip' => $this->last_ip)));
        return $accounts;
    }

    function get_bans() {
        $bans = AccountBan::find('all', array('conditions' => array('id' => $this->id),'order' => 'bandate DESC' ));
        return $bans;
    }

    function get_access_levels() {
        $access_levels = AccountAccess::find('all', array('conditions' => array('id' => $this->id)));
        return $access_levels;
    }

    function get_access_realms() {
        $levels = $this->get_access_levels();
        $realms = array();
        foreach ($levels as $level) {
            if ($level->gmlevel >= Environment::get_config_value('min_gm_level', 'access')) {
                if ($level->realmid == -1) {
                    $realms = Realm::find('all');
                    break;
                } else {
                    $realms = Realms::find('first', array('conditions' => array('realmid' => $level->realmid)));
                }
            }
        }
        return $realms;
    }
    
    function get_comments(){
        $comments = Comment::find('all', array('conditions' => array('account_id' => $this->id),'order' => 'created_at DESC'));
        return $comments;
    }
    
    function get_partners(){
        $partners = AccountPartner::find('all', array('conditions' => array('account_id = :account_id OR partner_id = :account_id','account_id' => $this->id)));
        return $partners;
    }
    
    function get_deleted_characters(){
        $del_chars = array();
        foreach($this->realms as $realm){
            $result = $realm->get_characters('all', array('conditions' => array('deleteInfos_Account = :accid', ':accid' => $this->id)));
            if(is_array($result))
                $del_chars += $result;
        }
        
        return $del_chars;
    }
    
    //---------------------------------------------------------------------------
    //-- Virtual Attributes
    //---------------------------------------------------------------------------
    function get_lowest_gm_level() {
        $access_level = AccountAccess::find('first', array(
                    'conditions' => array('id' => $this->id),
                    'order' => 'gmlevel ASC'
                ));
        return $access_level->gmlevel;
    }

    function get_highest_gm_level() {
        $access_level = AccountAccess::find('first', array(
                    'order' => 'gmlevel DESC',
                    'conditions' => array('id' => $this->id)
                ));
        return $access_level->gmlevel;
    }

    public function get_banned() {
        $ban = AccountBan::find('first', array('conditions' => array('id' => $this->id, 'active' => 1)));
        if ($ban) {
            return true;
        } else {
            return false;
        }
    }

    public function get_online() {
        $realms = Realm::find('all');

        $online = false;
        foreach ($this->characters as $char) {
            if ($char->online == 1) {
                $online = true;
                break;
            }
        }
        return $online;
    }

    //---------------------------------------------------------------------------
    //-- Static Functions
    //---------------------------------------------------------------------------
    static public function hash_password($username, $password) {
        return strtoupper(sha1(strtoupper($username) . ":" . strtoupper($password)));
    }

}
