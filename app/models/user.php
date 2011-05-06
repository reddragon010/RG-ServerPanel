<?php
class User extends Model {
	static $dbname = 'login';
	static $table = 'account';
	static $joined_tables = array(
		array('type' => 'LEFT', 'table' => 'account_access', 'key' => 'id', 'fields' => array('gmlevel'))
	);
  static $fields = array('id','username','email', 'expansion', 'joindate', 'last_ip', 'locked');
	
	public function before_save(){
		$this->sha_pass_hash = $this->hash_password($this->username,$this->password);
		return true;
	}
	
	//---------------------------------------------------------------------------
	//-- Basic Auth
	//---------------------------------------------------------------------------
	public static function login($username,$password,$set_session=true){
		$pass_hash = User::hash_password($username,$password);
		$user = User::find('first',array('conditions' => array('username = ? AND sha_pass_hash = ?', $username, $pass_hash), 'limit' => 1));
    if($user){        
			if($set_session==true){
				$_SESSION['userid'] = $user->id;
				$_SESSION['userdata'] = $user->data;
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
	//-- Misc Stuff
	//---------------------------------------------------------------------------
	public function get_online(){
		$realms = Realm::find('all');
		
		$online = false;
		foreach($realms as $realm){
			$online_char = $realm->find_characters('first',array('conditions' => array('online = 1')));
			if(!empty($online_char)){
				$online = true;
			}
		}
		return $online;
	}
	
	public function get_gmlevel(){
		return $this->gmlevel;
	}
	
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
				$_SESSION['userdata'] = $this->data;
			}
			return true;
		} else{
			return false;
		}
	}
	
	//---------------------------------------------------------------------------
	//-- Private Function
	//---------------------------------------------------------------------------
	private static function hash_password($username,$password){
		return sha1(strtoupper($username) . ":" . strtoupper($password));
	}
}
