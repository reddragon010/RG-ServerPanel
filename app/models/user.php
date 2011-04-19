<?php
class User extends Model {
	var $token		= NULL;
	
	static $dbname = 'login';
	static $table = 'account';
   	
   	static $fields = array('id','username','gmlevel','email', 'expansion', 'joindate', 'last_ip', 'locked');
	
	public function before_save(){
		$this->sha_pass_hash = $this->hash_password($this->username,$this->password);
		return true;
	}
	
	public function after_build(){
		if($webuser = Webuser::find($this->id)){
			$this->webuser = $webuser;
            $this->get_mainchar();
		} 
	}
	
	//---------------------------------------------------------------------------
	//-- Basic Auth
	//---------------------------------------------------------------------------
	public static function login($username,$password,$set_session=true){
		$pass_hash = User::hash_password($username,$password);
		$user = User::find('first',array('conditions' => array('username = ? AND sha_pass_hash = ?', $username, $pass_hash), 'limit' => 1));
        if($user){
            if(empty($user->webuser)){
                $user->webuser = Webuser::create(array('id' => $user->id));
            }
			if($set_session==true){
				$_SESSION['userid'] = $user->id;
				$_SESSION['userdata'] = $user->_data;
			}
			return $user;
		} else{
			return false;
		}
	}
	
	public function logout(){
		if(!empty($_SESSION['userid'])){
			$_SESSION['userid'] 	= NULL;
			$_SESSION['user'] = NULL;
			session_unset(); 
			session_destroy();
			return true;
		}
    }
	
	//---------------------------------------------------------------------------
	//-- Validations
	//---------------------------------------------------------------------------
	public function validate(){
	  if(!isset($this->username)){
	    $this->errors[] = "Username is not defined!";
	  } else {
			if(User::find('first',array('conditions' => array("username = $this->username")))){
	    	$this->errors[] = "The Username is already in use, Please try another Username";
	    }
		}

	  if(!isset($this->password)){
	    $this->errors[] = "Please enter a Password";
	  } elseif(!$this->confirm) {
	    $this->errors[] = "Please Confirm the Password";
		} elseif(($this->password && $this->confirm) && ($this->password != $this->confirm)){
			$this->errors[] = "Passwords do not match!";
		}

	  if(!isset($this->email)){
	    $this->errors[] = "Please Enter your Email";
	  } else {
			if(User::find('first',array('conditions' => array("email = $this->email")))){
      	$this->errors[] = "That Email is Already in Use. Please try Another one";
      }
		}
		
		return empty($this->errors);
	}
	
	//---------------------------------------------------------------------------
	//-- Chars
	//---------------------------------------------------------------------------
	function get_characters(){
		$realms = Realm::find('all');
		
        $this->characters = array();
		foreach($realms as $realm){
			$this->characters += $realm->find_characters('all',array('conditions' => array('account = ? AND guid != ?', $this->id, $this->webuser->main_id)));
		}
		return $this->characters;
	}
	
	//---------------------------------------------------------------------------
	//-- Lost Password Functions
	//---------------------------------------------------------------------------
	function send_reset_password(){
		global $config;
		
		$key = uniqid();
		$this->update(array('lost_pw_key' => $key));
		$reset_link = $config['page_root'] . '/password_reset.php?key=' . $key;
		return send_mail('reset_password',$this->email,'[WOW] Du hast dein Passwort vergessen? Fail!',
										array('to' => $this->username, 'reset_link' => $reset_link));
	}
	
	public static function reset_password($key,$password){
		if($webuser = Webuser::find('first', array('conditions' => array('lost_pw_key' => $key), 'limit' => 1))){
			$user = User::find($webuser->id);
			$pass_hash = sha1(strtoupper($user->username) . ":" . strtoupper($password)); 
			$user->update(array('sha_pass_hash' => $pass_hash));
			$webuser->update(array('lost_pw_key' => 'NULL'));
			return true;
		} else {
			return false;
		}
	}
	
	//---------------------------------------------------------------------------
	//-- Misc Stuff
	//---------------------------------------------------------------------------
	public function logged_in() {
		if(!empty($this->id) && !empty($_SESSION['userid'])){
			return true;
		} else {
			return false;
		}
	}
	
	public function is_admin() {
		if($this->gmlevel >= 3){
			return true;
		} else {
			return false;
		}
	}
	
	public function is_gm() {
		if($this->gmlevel >= 2){
			return true;
		} else {
			return false;
		}
	}
	
	public function reload($set_session=true){
		if(parent::reload()){
			if($set_session){
				$_SESSION['userid'] = $this->userid;
				$_SESSION['userdata'] = $this->_data;
			}
			return true;
		} else{
			return false;
		}
	}
	
	//---------------------------------------------------------------------------
	//-- Private Function
	//---------------------------------------------------------------------------
	private function gen_token(){
		return sha1(uniqid());
	}
	
	private static function hash_password($username,$password){
		return sha1(strtoupper($username) . ":" . strtoupper($password));
	}
}
