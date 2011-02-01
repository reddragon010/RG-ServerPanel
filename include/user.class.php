<?php
require_once(dirname(__FILE__) . '/common.inc.php');
require_once(dirname(__FILE__) . '/character.class.php');
require_once(dirname(__FILE__) . '/functions_mail.php');

class User {
	var $loaded 			= false;
	var $userid 			= NULL;
	var $userdata			= array();
	var $chars 				= array();
	var $webdata			= array();
	
	private $realmdb 	= NULL;
	private $chardb 	= NULL;
	private $webdb 		= NULL;
	var $token		= NULL;
		
	public function __construct(){
		global $config;
		
		$this->chardb = new Database($config,$config['db']['chardb']);
		$this->webdb = new Database($config,$config['db']['webdb']);
		$this->realmdb = new Database($config,$config['db']['realmdb']);
		if(!isset($_SESSION)) session_start();
		if(!empty($_SESSION['userid'])){
			$this->loadUser($_SESSION['userid']);
		}
	}
	
	//---------------------------------------------------------------------------
	//-- Basic Auth
	//---------------------------------------------------------------------------
	public function register($username, $password, $email, $flags){
		$pass_hash = sha1(strtoupper($username) . ":" . strtoupper($password));
		$sql = "INSERT INTO `account`
            (`username`,`sha_pass_hash`,`email`,`expansion`)
           	VALUES ('".$username."','".$password."','".$email."','".$flags."')";
		$this->realmdb->query($sql);
		return true;
	}
	
	public function login($username,$password,$set_session=true){
		$pass_hash = sha1(strtoupper($username) . ":" . strtoupper($password));
		$sql = "SELECT id,username,gmlevel,email FROM `account` WHERE `username`='".protect($username)."' AND `sha_pass_hash` = '".$pass_hash."' LIMIT 1";
		$this->realmdb->query($sql);
		if($this->realmdb->count() == 0){
			return false;
		} else{
			$this->userdata = $this->realmdb->fetchRow();
			$this->userid = $this->userdata['id'];
			$sql = "SELECT * FROM `account` WHERE `id`=".$this->userid." LIMIT 1";
			$this->webdb->query($sql);
			if($this->webdb->count() > 0){
				$this->webdata = $this->webdb->fetchRow();
			} else {
				$sql = "INSERT INTO `account` (`id`) VALUES (".$this->userid.")";
				$this->webdb->query($sql);
			}
			if($set_session==true){
				$_SESSION['userid'] = $this->userid;
				$_SESSION['userdata'] = $this->userdata;
				$_SESSION['webdata'] = $this->webdata;
			}
			flash('success','Login successful!');
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
			flash('success', "erfolgreich ausgeloggt!");
			return true;
		}
  }
	
	//---------------------------------------------------------------------------
	//-- Chars
	//---------------------------------------------------------------------------
	function fetchChars(){
		if($this->webdata['main_id']){
			$sql = "SELECT `guid` FROM `characters` WHERE `account`=".$this->userid." AND `guid` != ".$this->webdata['main_id'];
		} else {
			$sql = "SELECT `guid` FROM `characters` WHERE `account`=".$this->userid;
		}
		$this->chardb->query($sql);
		if($this->chardb->count() > 0){
			while($row = $this->chardb->fetchRow()){
				$char = new Character($row['guid']);
				$char->fetchData();
				$this->chars[] = $char;
			}
		}
	}
	
	function fetchMainChar(){
		$char = new Character($this->webdata['main_id']);
		if($char->fetchData()){
			return $char;
		} else {
			return false;
		}
	}
	
	function setMainChar($guid){
		$sql = "UPDATE `account` SET `main_id`=".$guid." WHERE `id`=".$this->userid;
		$this->webdb->query($sql);
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
			if(send_mail('friend_send_token', $friend->userdata['email'], '[WOW] Wirb Einen Freund', 
								array('toname' => $friend->userdata['username'],
							 				'fromname' => $this->userdata['username'],
							 				'tokenlink' => $config['root_host'] . $config['root_url'] . '/index.php?a=invite&token=' . $this->token,
							 				'hplink' => $config['root_host'] . $config['root_url'])))
			{
				return true;				
			}
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
			$this->webdb->query($sql);
			return true;
		} else {
			return false;
		}
	} 
	
	function mark_friend_token($friend){
		if(!empty($this->token)){
			$sql  = "UPDATE `account` SET `tokens`=`tokens` - 1 WHERE `id`=$this->userid";
			$sql2 = "UPDATE `friend_token` SET `friend_id`=$friend->userid WHERE `token`='$this->token' AND `taken`=0";
			$this->webdb->query($sql);
			$this->webdb->query($sql2);
			$this->reload();
		}
	}
	
	function use_friend_token($token){
		$sql = "SELECT * FROM `friend_token` WHERE `token`='$token' AND `taken`=0 LIMIT 1";
		$this->webdb->query($sql);
		if($this->webdb->count() > 0){
			$row = $this->webdb->fetchRow();
			if($row['friend_id']==$this->userid){
				$sql = "SELECT * FROM `account_friends` WHERE `id`={$row['account_id']} AND `friend_id`={$row['friend_id']}";
				$this->realmdb->query($sql);
				if($this->realmdb->count() == 0){
					$exp_date = date("Y-m-d", strtotime('-30 days'));
					$sql = "INSERT INTO `account_friends` (`id`,`friend_id`, `expire_date`) VALUES ('".$row['account_id']."','".$row['friend_id']."','".$exp_date."')";
					$this->realmdb->query($sql);
					$sql = "UPDATE `friend_token` SET `taken`='1' WHERE `token`='$token'";
					$this->webdb->query($sql);
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
	//-- Misc Stuff
	//---------------------------------------------------------------------------
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
		$this->realmdb->query($sql);
		if($this->realmdb->count() == 0){
			return false;
		} else{
			$this->userdata = $this->realmdb->fetchRow();
			$this->userid = $this->userdata['id'];
			$sql = "SELECT * FROM `account` WHERE `id`=".$this->userid." LIMIT 1";
			$this->webdb->query($sql);
			if($this->webdb->count() > 0){
				$this->webdata = $this->webdb->fetchRow();
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
}
?>