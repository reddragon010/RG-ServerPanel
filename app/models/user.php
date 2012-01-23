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

class User {
    static $current;
    
    public $account;
    public $id;
    public $username;
    private $password_hash;
    private $dummy;
    
    private $roles = array(
        0 => 'guest',
        1 => 'user',
        2 => 'vip',
        3 => 'trial-gm',
        4 => 'gm',
        5 => 'lead-gm'
    );

    public function __construct($usernameOrId, $password="", $dummy=true) {
        if (is_numeric($usernameOrId)) {
            $this->load_session_data();
            $this->load_account();
        } elseif (!empty($usernameOrId) && !empty($password)) {
            $this->username = $usernameOrId;
            $this->password_hash = Account::hash_password($usernameOrId, $password);
        } elseif (!empty($usernameOrId) && $dummy){
            $this->dummy = $dummy;
            $this->username = $usernameOrId;
            $this->account = Account::build(array(
                'username' => $usernameOrId,
                'id' => 0
            ));
        } else {
            throw new Exception("Invalid Constructor on User");
        }
    }
    
    public static function load_current_user(){
        if (!isset(self::$current) && !empty($_SESSION['userid'])) {
            self::$current = new User($_SESSION['userid']);
            Debug::add('Loading current user ' . var_export(self::$current,true));
        }
        return true;
    }
    
    //---------------------------------------------------------------------------
    //-- Basic Auth
    //---------------------------------------------------------------------------
    public function login() {
        if($this->load_account()){
            $this->set_session_data();
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        if (!empty($_SESSION['userid'])) {
            self::clear_session();
            return true;
        }
    }

    public static function clear_session() {
        $_SESSION['userid'] = NULL;
        $_SESSION['username'] = NULL;
        $_SESSION['userpasshash'] = NULL;
        session_unset();
        session_destroy();
    }

    public function reload() {
        $this->load_account();
        $this->set_session();
        return true;
    }

    private function load_account() {
        $account = Account::find()->where(array(
            'username' => $this->username, 
            'sha_pass_hash' => $this->password_hash
        ))->first();
        
        if ($account && 
        strtoupper($account->sha_pass_hash) == strtoupper($this->password_hash) && 
        strtoupper($account->username) == strtoupper($this->username)) {
            $this->account = $account;
            $this->id = $account->id;
            $this->username = $account->username;
            return true;
        } else {
            return false;
        }
    }

    /*
      public function get_gmlevel() {
      return $this->gmlevel;
      }
     */

    //---------------------------------------------------------------------------
    //-- Misc Stuff
    //---------------------------------------------------------------------------
    public function logged_in() {
        if (!empty($this->id) && !empty($_SESSION['userid'])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_role(){
        return $this->roles[$this->account->highest_gm_level];
    }
    
    public function is_permitted_to($action,$controller){
        return Permissions::check_permission($controller, $action, $this->get_role());
    }
    
    public function is_dummy(){
        return $this->dummy;
    }

    //---------------------------------------------------------------------------
    //-- Privates
    //---------------------------------------------------------------------------
    private function set_session_data() {
        $_SESSION['userid'] = $this->account->id;
        $_SESSION['username'] = $this->account->username;
        $_SESSION['userpasshash'] = $this->password_hash;
    }

    private function load_session_data() {
        $this->id = $_SESSION['userid'];
        $this->username = $_SESSION['username'];
        $this->password_hash = $_SESSION['userpasshash'];
    }

}
