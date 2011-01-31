<?php
require_once(dirname(__FILE__) . '/common.inc.php');

class User {
	var $loaded = false;
	var $userid = NULL;
	var $userdata = NULL;
	
	private $realmdb = NULL;
		
	public function __construct(){
		global $config;
		
		$this->realmdb = new Database($config,$config['db']['realmdb']);
		if(!isset($_SESSION)) session_start();
		if(!empty($_SESSION['userid'])){
			$this->loadUser($_SESSION['userid']);
		}
	}
	
	public function register($username, $password, $email, $flags){
		$pass_hash = sha1(strtoupper($username) . ":" . strtoupper($password));
		$sql = "INSERT INTO `account`
            (`username`,`sha_pass_hash`,`email`,`expansion`)
           	VALUES ('".$username."','".$password."','".$email."','".$flags."')";
		$this->realmdb->query($sql);
		return true;
	}
	
	public function login($username,$password){
		$pass_hash = sha1(strtoupper($username) . ":" . strtoupper($password));
		$sql = "SELECT id,username,gmlevel,email FROM `account` WHERE `username`='".protect($username)."' AND `sha_pass_hash` = '".$pass_hash."' LIMIT 1";
		$this->realmdb->query($sql);
		if($this->realmdb->count() == 0){
			return false;
		} else{
			$this->userdata = $this->realmdb->fetchArray();
			$this->userid = $this->userdata['id'];
			$_SESSION['userid'] = $this->userid;
			$_SESSION['userdata'] = $this->userdata;
			return true;
		}
	}
	
	function logout($redirectTo = '')
  {
    $this->userData = '';
		if(!empty($_SESSION['userid'])){
			session_unset (); 
			session_destroy();
		}
    if (!headers_sent()){
			if($redirectTo != ''){
				header('Location: '.$redirectTo );
	  		exit;
			} else {
				header('Location: '.$config['root_url']);
				exit;
			}	
		}
  }
	
	private function loadUser($userid) {
		$this->realmdb->query("SELECT * FROM `account` WHERE `id` = '".$userid."' LIMIT 1");
	  if ( $this->realmdb->count() == 0 )
	    	return false;
	  $this->userdata = $this->realmdb->fetchArray();
	  $this->userid = $userid;
	  $_SESSION['userid'] = $this->userid;
		$_SESSION['userdata'] = $this->userdata;
	  return true;
	}
}
?>