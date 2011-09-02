<?php

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
        3 => 'trail-gm',
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
        $account = Account::find('first', array(
                    'conditions' => array('username' => $this->username, 'sha_pass_hash' => $this->password_hash)
                ));
        if ($account && $account->sha_pass_hash == $this->password_hash && $account->username == $this->username) {
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
