<?php
require_once(dirname(__FILE__) . '/../common.php');

//---------------------------------------------------------------------------
//-- Server Tools
//---------------------------------------------------------------------------
function getServerStatus(){
	global $config;
  if (! $sock = @fsockopen($config['worldserver']['ip'], $config['worldserver']['port'], $num, $error, 3)) 
    echo 'SERVER <font color="green">ON</font><br />';
  else{ 
    echo 'SERVER <font color="red">OFF</font><br />';
    fclose($sock); 
  }
}

function getServerUptime(){
	global $config;
  $db = new Database($config,$config['db']['chardb']);
  
  $sql = "SELECT * FROM " . $config['db']['realmdb'] . ".`uptime` ORDER BY `starttime` DESC LIMIT 1"; 
 	$db->query($sql);
  $uptime_results = $db->fetchRow();    
  
  if ($uptime_results['uptime'] > 86400) { 
      $uptime =  round(($uptime_results['uptime'] / 24 / 60 / 60),2)." Days";
  }
  elseif($uptime_results['uptime'] > 3600) { 
      $uptime =  round(($uptime_results['uptime'] / 60 / 60),2)." Hours";
  }
  else { 
      $uptime =  round(($uptime_results['uptime'] / 60),2)." Min";
  }
  
  echo "Uptime: <b>$uptime </b>";
  
}

//---------------------------------------------------------------------------
//-- Online Players
//---------------------------------------------------------------------------
function getPlayersOnline(){
	global $config;
  $db = new Database($config,$config['db']['chardb']); 
 
  $db->query("SELECT * FROM characters WHERE online='1' ORDER BY NAME");
  return $db->count();
  
}

function getPlayersOnlineCount(){
	global $config;
  $db = new Database($config,$config['db']['chardb']);
      
  $sql = "SELECT Count(Online) FROM `characters` WHERE `online` = 1";
  $db->query($sql);
  $row = $db->fetchRow();
  $online = $row["Count(Online)"];
              
  echo 'Online Players: <b>' .$online.'</b>';
}

function getPlayersHordeOnlineCount(){
	global $config;
  $db = new Database($config,$config['db']['chardb']);
      
  $sql = "SELECT Count(Online) FROM `characters` WHERE `online` = 1 AND `race` IN (2, 5, 6, 8, 10)";
  $db->query($sql);
  $row = $db->fetchRow;
  $online = $row["Count(Online)"];
              
  echo 'Online Horde: <b>'.$online.'</b>'; 
}

function getPlayersAllianzOnlineCount(){
	global $config;
  $db = new Database($config,$config['db']['chardb']);
      
  $sql = "SELECT Count(Online) FROM `characters` WHERE `online` = 1 AND `race` IN (1, 3, 4, 7, 11)";
  $db->query($sql);
  $row = $db->fetchRow;
  $online = $row["Count(Online)"];
              
  echo 'Online Allianz: <b> '.$online.' </b>';
}

//---------------------------------------------------------------------------
//-- checkers
//---------------------------------------------------------------------------
function check_registration($username, $password, $confirm, $email){
	global $config;
	
	$db = new Database($config,$config['db']['realmdb']);
	$errors = array();

  if(!$username){
      $errors[] = "Username is not defined!";
  }

  if(!$password){
    $errors[] = "Please enter a Password";
  } elseif(!$confirm) {
    $errors[] = "Please Confirm the Password";
	} elseif(($password && $confirm) && ($password != $confirm)){
		$errors[] = "Passwords do not match!";
	}

  if(!$email){
      $errors[] = "Please Enter your Email";
  }

  if($username){
      $sql = "SELECT * FROM `account` WHERE `username`='".$username."'";
      $db->query($sql);

          if($db->count() > 0){
              $errors[] = "The Username is already in use, Please try another Username";
          }
  }

  if($email){
      $sql = "SELECT * FROM `account` WHERE `email`='".$email."'";
      $db->query($sql);

          if($db->count() > 0){
              $errors[] = "That Email is Already in Use. Please try Another one";
          }

  }
	return $errors;
}

//---------------------------------------------------------------------------
//-- User Helpers
//---------------------------------------------------------------------------
function userid_by_email($email){
	global $config;
	$db = new Database($config, $config['db']['realmdb']);
	$sql = "SELECT `id` FROM `account` WHERE `email`='$email'";
	$db->query($sql);
	if($db->count() > 0){
		$row=$db->fetchRow();
		return $row['id'];
	} else {
		return false;
	}
}

function logged_in(){
	if(!empty($user->userid) && !empty($_SESSION['userid'])){
		return true;
	} else {
		return false;
	}
}
//---------------------------------------------------------------------------
//-- Visual Helpers
//---------------------------------------------------------------------------
function display_money($money){
	if($money < 100){
		$g = 0;
		$s = 0;
		$k = $money;
	} elseif($money < 1000) {
		$g = 0;
		$s = intval($money/100);
		$k = $money - $s*100;
	} else {
		$g = intval($money/1000);
		$s = intval(($money - $g*1000)/100);
		$k = $money - ($g*1000+$s*100);
	}
	return "{$g}g {$s}s {$k}k";
}

function display_avatar($char){
	if($char->data['level'] < 20){
		$path = "images/avatars/def/";
	} elseif($char->data['level'] < 60) {
		$path = "images/avatars/wow/";
	} elseif($char->data['level'] < 70) {
		$path = "images/avatars/60/";
	} elseif($char->data['level'] < 80) {
		$path = "images/avatars/70/";
	} elseif($char->data['level'] == 80) {
		$path = "images/avatars/80/";
	}
	return $path . $char->data['gender'] . "-" . $char->data['race'] . "-" . $char->data['class'] . ".gif";
}

//---------------------------------------------------------------------------
//-- Mail Helpers
//---------------------------------------------------------------------------
function send_mail($tpl, $to, $subject, $data){
	global $config;
	$header = 'From: ' . $config['mail']['from'] . "\r\n" .
	    			'Reply-To: ' . $config['mail']['reply'] . "\r\n" .
	    			'X-Mailer: PHP/' . phpversion();
	$text		= tpl_load(file_get_contents('mail/' . $tpl . '.php'),$data);
	return mail($to,$subject,$text,$header);
}

function tpl_load($text, $data){
	foreach($data as $k => $v){
		$text = str_replace("%{$k}%",$v,$text);
	}
	return $text;
}


//---------------------------------------------------------------------------
//-- Misc Helper Functions
//---------------------------------------------------------------------------
function protect($string){
    $string = mysql_real_escape_string($string);
    $string = strip_tags($string);
    $string = addslashes($string);
    return $string;
}

function root_url() {
	global $config;
 	$pageURL = 'http';
 	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 	$pageURL .= "://";
 	if ($_SERVER["SERVER_PORT"] != "80") {
  	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$config['root_url'];
 	} else {
  	$pageURL .= $_SERVER["SERVER_NAME"].$config['root_url'];
 	}
 	return $pageURL;
}

function flash($type, $message, $hops=0){
	$_SESSION['flash'] = array();
	$_SESSION['flash']['msg'] = $message;
	$_SESSION['flash']['type'] = $type;
	$_SESSION['flash']['hops'] = $hops;
}

function flushflash(){
	if(isset($_SESSION['flash'])) {
		if($_SESSION['flash']['hops'] <= 0){
			$flash = $_SESSION['flash'];
			$_SESSION['flash'] = null;
			return $flash;
		} else {
			$_SESSION['flash']['hops'] = $_SESSION['flash']['hops']-1;
		}
	}
}
?>