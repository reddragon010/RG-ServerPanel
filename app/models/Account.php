<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of RG-ServerPanel.
 *
 *    RG-ServerPanel is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    RG-ServerPanel is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with RG-ServerPanel.  If not, see <http://www.gnu.org/licenses/>.
 */

class Account extends ApplicationModel {

    static $dbname = 'login';
    static $table = 'account';
    static $primary_key = 'id';
    static $name_field = 'username';
    static $plural = 'accounts';
    static $fields = array(
        'id',
        'username', 
        'sha_pass_hash',
        'email',
        'expansion',
        'joindate',
        'last_login',
        'last_ip',
        'locked',
        'v',
        's'
    );
    
    static $relations = array(
        'note' => array(
            'model' => 'AccountNote',
            'type' => 'has_one',
            'field' => 'account_id',
            'fk' => 'id'
        )
    );
    
    public function before_save($sql) {
        if (!empty($this->password)) {
            $this->sha_pass_hash = Account::hash_password($this->username, $this->password);
        }
        return true;
    }
    
    public $password = '';
    public $password_confirm = '';

    //---------------------------------------------------------------------------
    //-- Validations
    //---------------------------------------------------------------------------
    public function validate() {
        if ($this->online){
            $this->errors[] = "Account is online! Please SaveBan and Kick it to avoid 'The End Of The WO-World'!";
        }
        
        if (!isset($this->username)) {
            $this->errors[] = "Username is not defined!";
        } else {
            $account = Account::find()->where(array('username' => $this->username))->first();
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
            
        if($this->new){
            if (isset($this->email) && !empty($this->email)) {
                $account = Account::find()->where(array("email" => $this->email))->first();
                if ($account && $account->id != $this->id) {
                    $this->errors[] = "That email is already in use. Please try another one";
                }
            } else {
                $this->errors[] = "Please enter an email-address";
            }
        }
        
        return empty($this->errors);
    }

    //---------------------------------------------------------------------------
    //-- Relations
    //---------------------------------------------------------------------------
    function get_characters() {
        $characters = array();
        $realms = Realm::find()->available()->all();
        foreach ($realms as $realm) {
            $result = Character::find()->where(array('account' => $this->id))->realm($realm->id)->all();
            if(is_array($result))
                $characters += $result;
        }
        return $characters;
    }

    function get_accounts_with_same_ip() {
        $accounts = Account::find()->where(array('last_ip = :last_ip AND id <> :id', 'last_ip' => $this->last_ip, 'id' => $this->id))->all();
        return $accounts;
    }

    function get_bans() {
        $bans = AccountBan::find()->where(array('id' => $this->id))->order('bandate DESC' )->all();
        return $bans;
    }

    function get_access_levels() {
        $access_levels = AccountAccess::find()->where(array('id' => $this->id))->all();
        return $access_levels;
    }

    function get_access_realms() {
        $levels = $this->get_access_levels();
        $realms = array();
        foreach ($levels as $level) {
            if ($level->gmlevel >= Environment::get_value('min_gm_level', 'access')) {
                if ($level->realmid == -1) {
                    $realms = Realm::find()->available()->all();
                    break;
                } else {
                    $realms = Realms::find()->where(array('realmid' => $level->realmid))->first();
                }
            }
        }
        return $realms;
    }
    
    function get_comments(){
        $comments = Comment::find()->where(array('account_id' => $this->id))->order('created_at DESC')->all();
        return $comments;
    }
    
    function get_partners(){
        $partners = AccountPartner::find()->where(array('(account_id = :account_id OR partner_id = :account_id) AND (until IS NULL OR until > :now)','account_id' => $this->id, 'now' => time()))->all();
        return $partners;
    }
    
    function get_deleted_characters(){
        $del_chars = array();
        $realms = Realm::find()->available()->all();
        foreach($realms as $realm){
            $result = Character::find()->where(array('deleteinfos_account' => $this->id))->realm($realm->id)->all();
            if(is_array($result))
                $del_chars += $result;
        }
        
        return $del_chars;
    }
    
    //---------------------------------------------------------------------------
    //-- Virtual Attributes
    //---------------------------------------------------------------------------
    function get_lowest_gm_level() {
        $access_level = AccountAccess::find()
                ->where(array('id' => $this->id))
                ->order('gmlevel ASC')
                ->first();
        if(!is_object($access_level))
            $access_level = 0;
        return $access_level->gmlevel;
    }

    function get_highest_gm_level() {
        $access_level = AccountAccess::find()
                ->where(array('id' => $this->id))
                ->order('gmlevel DESC')
                ->first();
        if(!is_object($access_level))
            return 0;
        else
            return $access_level->gmlevel;
    }

    public function get_banned() {
        $ban = AccountBan::find()
                ->where(array('id' => $this->id, 'active' => 1))
                ->first();
        return !empty($ban);
    }

    public function get_online() {
        $online = false;
        foreach ($this->characters as $char) {
            if ($char->online == 1) {
                $online = true;
                break;
            }
        }
        return $online;
    }
    
    public function get_name(){
        return $this->username;
    }
    
    //---------------------------------------------------------------------------
    //-- Functions
    //---------------------------------------------------------------------------
    public function lock(){
        if(!$this->locked == 1){
            $this->locked = 1;
            return $this->save();
        } else {
            $this->errors[] = 'Account already locked';
            return false;
        }
    }
    
    public function unlock(){
        if(!$this->locked == 0){
            $this->locked = 0;
            return $this->save();
        } else {
            $this->errors[] = 'Account not locked';
            return false;
        }
    }
    
    //---------------------------------------------------------------------------
    //-- Static Functions
    //---------------------------------------------------------------------------
    static public function hash_password($username, $password) {
        return strtoupper(sha1(strtoupper($username) . ":" . strtoupper($password)));
    }

}
