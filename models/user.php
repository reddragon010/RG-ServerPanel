<?php
class User extends Model {
	var $loaded 			= false;
	var $characters	= array();
	var $mainchar			= NULL;
	var $webuser			= NULL;
	
	var $token		= NULL;
	
	static $dbname = 'login';
	static $table = 'account';
	
	static $fields = array('id','username','gmlevel','email');
	
	public function before_save(){
		$this->sha_pass_hash = $this->hash_password($this->username,$this->password);
		return true;
	}
	
	public function after_find(){
		if($webuser = Webuser::find($this->id)){
			$this->webuser = $webuser;
		} else {
			$this->webuser = Webuser::create(array('id' => $this->id));
		}
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
				$_SESSION['user'] = $user;
			}
			return $user;
		} else{
			return false;
		}
	}
	
	public function logout()
  {
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
		
		foreach($realms as $realm){
			$this->characters += $realm->get_chararacters('all',array('conditions' => 'account = ? AND id != ?', $this->id. $this->main_char));
		}
		return $this->characters;
	}
	
	function get_mainchar(){
		if($this->webdata['main_id']){
			$char = new Character($this->webdata['main_id'],$this->webdata['main_realm']);
			if($char->fetchData()){
				$this->mainchar = $char;
				return $char;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function set_mainchar($guid, $realm_id){
		$this->update(array('main_id' => $guid, 'main_realm' => $realm_id));
		$this->reload();
		return true;
	}
	
	//---------------------------------------------------------------------------
	//-- Friend System
	//---------------------------------------------------------------------------
	function send_friend_invite($friend){
		global $config;
		
		if($this->gen_friend_token()){
			$this->mark_friend_token($friend);
			send_mail('friend_send_token', $friend->userdata['email'], '[WOW] Wirb Einen Freund', 
								array('toname' => $friend->userdata['username'],
							 				'fromname' => $this->userdata['username'],
							 				'tokenlink' => $config['root_host'] . $config['root_base'] . '/index.php?a=invite&token=' . $this->token,
							 				'hplink' => $config['root_host'] . $config['root_base']));
			return true;				
		} else {
			flash('error', 'Du hast keine Tokens mehr zu vergeben.');
			return false;
		}
	}
	
	function gen_friend_token(){
		$this->reload();
		if($this->webdata['tokens'] > 0){
			$this->token = $this->gen_token();
			$sql = "INSERT INTO `friend_token` (`token`,`account_id`) VALUES ('".$this->token."','".$this->userid."')";
			$this->db_web->query($sql);
			return true;
		} else {
			return false;
		}
	} 
	
	function mark_friend_token($friend){
		if(!empty($this->token)){
			$sql  = "UPDATE `account` SET `tokens`=`tokens` - 1 WHERE `id`=$this->userid";
			$sql2 = "UPDATE `friend_token` SET `friend_id`=$friend->userid WHERE `token`='$this->token' AND `taken`=0";
			$this->db_web->query($sql);
			$this->db_web->query($sql2);
			$this->reload();
		}
	}
	
	function use_friend_token($token){
		$sql = "SELECT * FROM `friend_token` WHERE `token`='$token' AND `taken`=0 LIMIT 1";
		$this->db_web->query($sql);
		if($this->db_web->count() > 0){
			$row = $this->db_web->fetchRow();
			if($row['friend_id']==$this->userid){
				$sql = "SELECT * FROM `account_friends` WHERE `id`={$row['account_id']} AND `friend_id`={$row['friend_id']}";
				$this->db_login->query($sql);
				if($this->db_login->count() == 0){
					$exp_date = date("Y-m-d", strtotime('-30 days'));
					$sql = "INSERT INTO `account_friends` (`id`,`friend_id`, `expire_date`) VALUES ('".$row['account_id']."','".$row['friend_id']."','".$exp_date."')";
					$this->db_login->query($sql);
					$sql = "UPDATE `friend_token` SET `taken`='1' WHERE `token`='$token'";
					$this->db_web->query($sql);
					flash('success', 'Token wurde erfolgreich eingelöst');
					return true;
				} else {
					flash('error', 'Du hast bereits eine laufende Freundschaftsverknüpfung mit dieser Spieler');
					return false;
				}
			} else {
				flash('error', 'Dieser Token gehört nicht dir!');
				return false;
			}
		} else {
			flash('error', 'Der Token ist invalid');
			return false;
		}
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
	function logged_in() {
		if(!empty($this->userid) && !empty($_SESSION['userid'])){
			return true;
		} else {
			return false;
		}
	}
	
	function is_admin() {
		if(isset($this->userdata) && $this->gmlevel >= 3){
			return true;
		} else {
			return false;
		}
	}
	
	function is_gm() {
		if(isset($this->userdata) && $this->gmlevel >= 2){
			return true;
		} else {
			return false;
		}
	}
	
	//---------------------------------------------------------------------------
	//-- Private Function
	//---------------------------------------------------------------------------
	public function reload($set_session=true){
		if(parent::reload()){
			if($set_session){
				$_SESSION['userid'] = $this->userid;
				$_SESSION['user'] = $this;
			}
			return true;
		} else{
			return false;
		}
	}
	
	private function gen_token(){
		return sha1(uniqid());
	}
	
	private static function hash_password($username,$password){
		return sha1(strtoupper($username) . ":" . strtoupper($password));
	}
}
