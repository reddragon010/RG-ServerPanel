<?php
class User {
	var $loaded 			= false;
	var $userid 			= NULL;
	var $userdata			= array();
	var $chars 				= array();
	var $mainchar			= NULL;
	var $webdata			= array();
	
	var $token		= NULL;
	
	private $db_web;
	private $db_login;
		
	public function __construct(){
		global $config;
		$this->db_login = new Database($config['login'],true);
		$this->db_web = new Database($config['web'],true);
		
		if(!empty($_SESSION['userid'])){
			$this->loadUser($_SESSION['userid']);
		}
	}
	
	//---------------------------------------------------------------------------
	//-- Basic Auth
	//---------------------------------------------------------------------------
	public function register($username, $password, $email, $flags){
		$username = strtoupper($username);
		$pass_hash = $this->hash_password($username,$password);
		$sql = "INSERT INTO `account`
            (`username`,`sha_pass_hash`,`email`,`expansion`)
           	VALUES ('".$username."','".$pass_hash."','".$email."','".$flags."')";
		$this->db_login->query($sql);
		return true;
	}
	
	public function login($username,$password,$set_session=true){
		$pass_hash = $this->hash_password($username,$password);
		$sql = "SELECT id,username,gmlevel,email FROM `account` WHERE `username`='".protect($username)."' AND `sha_pass_hash` = '".$pass_hash."' LIMIT 1";
		$this->db_login->query($sql);
		if($this->db_login->count() == 0){
			return false;
		} else{
			$this->userdata = $this->db_login->fetchRow();
			$this->userid = $this->userdata['id'];
			$sql = "SELECT * FROM `account` WHERE `id`=".$this->userid." LIMIT 1";
			$this->db_web->query($sql);
			if($this->db_web->count() > 0){
				$this->webdata = $this->db_web->fetchRow();
			} else {
				$sql = "INSERT INTO `account` (`id`) VALUES (".$this->userid.")";
				$this->db_web->query($sql);
				$sql = "SELECT * FROM `account` WHERE `id`=".$this->userid." LIMIT 1";
				$this->db_web->query($sql);
				$this->webdata = $this->db_web->fetchRow();
			}
			if($set_session==true){
				$_SESSION['userid'] = $this->userid;
				$_SESSION['userdata'] = $this->userdata;
				$_SESSION['webdata'] = $this->webdata;
			}
			return true;
		}
	}
	
	function logout()
  {
		if(!empty($_SESSION['userid'])){
			$_SESSION['userid'] 	="";
			$_SESSION['userdata'] ="";
			$_SESSION['webdata'] 	="";
			session_unset(); 
			session_destroy();
			return true;
		}
  }
	
	//---------------------------------------------------------------------------
	//-- Chars
	//---------------------------------------------------------------------------
	function fetchChars(){
		global $realms;
		
		foreach($realms as $realm){
			$this->chars = $this->chars + $realm->get_user_chars($this->userid,array(),$this->webdata['main_id']);
		}
	}
	
	function fetchMainChar(){
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
	
	function setMainChar($guid, $realm_id){
		
		$sql = "UPDATE `account` SET `main_id`=".$guid.", `main_realm`=".$realm_id." WHERE `id`=".$this->userid;
		$this->db_web->query($sql);
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
		$sql = "UPDATE `account` SET `lost_pw_key`='$key' WHERE `id`={$this->userid}";
		$this->db_web->query($sql);
		$reset_link = $config['root_host'] . $config['root_base'] . '/password_reset.php?key=' . $key;
		return send_mail('reset_password',$this->userdata['email'],'[WOW] Du hast dein Passwort vergessen? Fail!',
										array('to' => $this->userdata['username'], 'reset_link' => $reset_link));
	}
	
	public static function validate_reset_password_key($key){
		
		$sql = "SELECT `id` FROM `account` WHERE `lost_pw_key`='$key' LIMIT 1";
		$this->db_web->query($sql);
		return ($this->db_web->count() > 0);
	}
	
	public static function reset_password($key,$password){
		
		$sql = "SELECT `id` FROM `account` WHERE `lost_pw_key`='$key' LIMIT 1";
		$this->db_web->query($sql);
		if($this->db_web->count() > 0){
			$web_user = $this->db_web->fetchRow();
			$sql = "SELECT `username` FROM `account` WHERE `id`='" . $web_user['id'] . "' LIMIT 1";
			$this->db_login->query($sql);
			$realm_user = $this->db_login->fetchRow();
			$pass_hash = sha1(strtoupper($realm_user['username']) . ":" . strtoupper($password)); 
			$sql = "UPDATE `account` SET `sha_pass_hash`='$pass_hash' WHERE `id`=".$web_user['id'];
			$this->db_login->query($sql);
			$sql = "UPDATE `account` SET `lost_pw_key`=NULL WHERE `id`=".$web_user['id'];
			$this->db_web->query($sql);
			return true;
		} else {
			return false;
		}
		
	}
	
	//---------------------------------------------------------------------------
	//-- Changing Mail/Password 
	//---------------------------------------------------------------------------
	function change_password($password){
		
		$pass_hash = $this->hash_password($this->userdata['username'],$password);
		$sql = "UPDATE `account` SET `sha_pass_hash`='$pass_hash' WHERE `id`=".$this->userid;
		$this->db_login->query($sql);
		$this->reload();
		return true;
	}
	
	function change_email($email){
		
		$sql = "UPDATE `account` SET `email`='$email' WHERE `id`=".$this->userid;
		$this->db_login->query($sql);
		$this->reload();
		return true;
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
		if(isset($this->userdata) && $this->userdata['gmlevel'] >= 3){
			return true;
		} else {
			return false;
		}
	}
	
	function loadUser($userid,$set_session=true) {
		if($set_session){
	  	$this->userid = $_SESSION['userid'];
			$this->userdata = $_SESSION['userdata'];
			$this->webdata = $_SESSION['webdata'];
		} else {
			$this->userid = $userid;
			$this->reload(false);
		}
	  return true;
	}
	
	//---------------------------------------------------------------------------
	//-- Private Function
	//---------------------------------------------------------------------------
	private function reload($set_session=true){
		$sql = "SELECT id,username,gmlevel,email FROM `account` WHERE `id`=".$this->userid." LIMIT 1";
		$this->db_login->query($sql);
		if($this->db_login->count() == 0){
			return false;
		} else{
			$this->userdata = $this->db_login->fetchRow();
			$this->userid = $this->userdata['id'];
			$sql = "SELECT * FROM `account` WHERE `id`=".$this->userid." LIMIT 1";
			$this->db_web->query($sql);
			if($this->db_web->count() > 0){
				$this->webdata = $this->db_web->fetchRow();
			}
			if($set_session){
				$_SESSION['userid'] = $this->userid;
				$_SESSION['userdata'] = $this->userdata;
				$_SESSION['webdata'] = $this->webdata;
			}
			return true;
		}
	}
	
	private function gen_token(){
		return sha1(uniqid());
	}
	
	private function hash_password($username,$password){
		return sha1(strtoupper($username) . ":" . strtoupper($password));
	}
}
