<?php

class User {

    public $account = null;
    public $id = null;
    public $username = null;
    private $password_hash = null;

    public function __construct($usernameOrId, $password="") {
        if (is_numeric($usernameOrId)) {
            $this->load_session_data();
            $this->load_account();
        } elseif(!empty($usernameOrId) && !empty($password)) {
            $this->username = $usernameOrId;
            $this->password_hash = $this->hash_password($usernameOrId, $password);
        } else {
            throw new Exception("Invalid Constructor on User");
        }
    }

    //---------------------------------------------------------------------------
    //-- Basic Auth
    //---------------------------------------------------------------------------
    public function login() {
        try{
            $this->load_account();
        } catch(Exception $e) {
            throw $e;
            return false;
        }
        $this->set_session_data();
        return true;
    }

    public function logout() {
        if (!empty($_SESSION['userid'])) {
            $_SESSION['userid'] = NULL;
            $_SESSION['username'] = NULL;
            $_SESSION['userpasshash'] = NULL;
            session_unset();
            session_destroy();
            return true;
        }
    }

    public function reload() {
        $this->load_account();
        $this->set_session();
        return true;
    }

    private function load_account() {
        $account = Account::find('first', array(
                    'conditions' => array('username' => $this->username, 'sha_pass_hash' => $this->password_hash)
                ));
        if ($account) {
            $this->account = $account;
            $this->id = $account->id;
            $this->username = $account->username;
        } else {
            throw new Exception('Login failed - Please recheck username and password');
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

    public function is_admin() {
        if ($this->gmlevel >= 3) {
            return true;
        } else {
            return false;
        }
    }

    public function is_gm() {
        if ($this->gmlevel >= 2) {
            return true;
        } else {
            return false;
        }
    }
    
    //---------------------------------------------------------------------------
    //-- Privates
    //---------------------------------------------------------------------------
    private function hash_password($username, $password) {
        return sha1(strtoupper($username) . ":" . strtoupper($password));
    }
    
    private function set_session_data(){
        $_SESSION['userid'] = $this->account->id;
        $_SESSION['username'] = $this->account->username;
        $_SESSION['userpasshash'] = $this->password_hash;
    }
    
    private function load_session_data(){
        $this->id = $_SESSION['userid'];
        $this->username = $_SESSION['username'];
        $this->password_hash = $_SESSION['userpasshash'];
    }
}
