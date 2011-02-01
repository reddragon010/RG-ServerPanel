<?php
require_once(dirname(__FILE__) . '/common.inc.php');

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





function getNews(){
	global $config;
	$db = new Database($config,$config['db']['webdb']);
      
	$sql = "SELECT * FROM `news` ORDER BY `date` DESC;";
	$db->query($sql);
	
	$numrows = $db->count();
	
	if($numrows > 0){
		
		while($row = $db->fetchRow()){;
		
			$id = $row["id"];
			$date = $row["date"];
			$title = $row["title"];
			$content = $row["content"];
			$author = $row["author"];
			#963
			echo "<font color=\"#963\">".$id."# ".$title.":</font><br />";
			echo "<font size=\"-1\">".$author.", ".$date."</font><br />";
			echo $content."<br /><br />";
		}
		
	}
}

//---------------------------------------------------------------------------
//-- checkers
//---------------------------------------------------------------------------
function check_registration($username, $password, $confirm, $email){
	global $config;
	
	$db = new Database($config,$config['db']['realmdb']);
	$errors = array();

  if(!$username){
      $errors[] = "<center><b>Username is not defined!</b></center>";
  }

  if(!$password){
    $errors[] = "<center><b>Please enter a Password</b></center>";
  } elseif(!$confirm) {
    $errors[] = "<center><b>Please Confirm the Password.</b></center>";
	} elseif(($password && $confirm) && ($password != $confirm)){
		$errors[] = "<center><b>Passwords do not match!</b></center>";
	}

  if(!$email){
      $errors[] = "<center><b>Please Enter your Email.</b></center>";
  }

  if($username){
      $sql = "SELECT * FROM `account` WHERE `username`='".$username."'";
      $db->query($sql);

          if($db->count() > 0){
              $errors[] = "<center><b>The Username is already in use, Please try another Username.</b></center>";
          }
  }

  if($email){
      $sql = "SELECT * FROM `account` WHERE `email`='".$email."'";
      $db->query($sql);

          if($db->count() > 0){
              $errors[] = "<center><b>That Email is Already in Use. Please try Another one.</b></center>";
          }

  }
	return $errors;
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

function redirect_to($url){
	echo '<script type="text/javascript">window.location = "'.$url.'"</script>';
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

function logged_in(){
	global $user;
	if(!empty($user->userid) && !empty($_SESSION['userid'])){
		return true;
	} else {
		return false;
	}
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