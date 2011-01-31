<?php
require_once(dirname(__FILE__) . '/common.inc.php');
require_once(dirname(__FILE__) . '/character.class.php');

class User {
	var $loaded 			= false;
	var $userid 			= NULL;
	var $userdata			= array();
	var $chars 				= array();
	var $webdata			= array();
	
	private $realmdb 	= NULL;
	private $chardb 	= NULL;
	private $webdb 	= NULL;
		
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
			$this->userdata = $this->realmdb->fetchRow();
			$this->userid = $this->userdata['id'];
			$sql = "SELECT `main_id` FROM `account` WHERE `id`=".$this->userid." LIMIT 1";
			$this->webdb->query($sql);
			if($this->webdb->count() > 0){
				$this->webdata = $this->webdb->fetchRow();
			} else {
				$sql = "INSERT INTO `account` (`id`) VALUES (".$this->userid.")";
				$this->webdb->query($sql);
			}
			$_SESSION['userid'] = $this->userid;
			$_SESSION['userdata'] = $this->userdata;
			$_SESSION['webdata'] = $this->webdata;
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
	
	private function reload(){
		$sql = "SELECT id,username,gmlevel,email FROM `account` WHERE `id`=".$this->userid." LIMIT 1";
		$this->realmdb->query($sql);
		if($this->realmdb->count() == 0){
			return false;
		} else{
			$this->userdata = $this->realmdb->fetchRow();
			$this->userid = $this->userdata['id'];
			$sql = "SELECT `main_id` FROM `account` WHERE `id`=".$this->userid." LIMIT 1";
			$this->webdb->query($sql);
			if($this->webdb->count() > 0){
				$this->webdata = $this->webdb->fetchRow();
			}
			$_SESSION['userid'] = $this->userid;
			$_SESSION['userdata'] = $this->userdata;
			$_SESSION['webdata'] = $this->webdata;
			return true;
		}
	}
	
	private function loadUser($userid) {
	  $this->userid = $_SESSION['userid'];
		$this->userdata = $_SESSION['userdata'];
		$this->webdata = $_SESSION['webdata'];
	  return true;
	}
}
?>