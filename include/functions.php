<?php
require_once(dirname(__FILE__) . '/../common.php');

//---------------------------------------------------------------------------
//-- checkers
//---------------------------------------------------------------------------
function check_registration($username, $password, $confirm, $email){
	global $config, $db_login;
	
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
      $db_login->query($sql);

          if($db_login->count() > 0){
              $errors[] = "The Username is already in use, Please try another Username";
          }
  }

  if($email){
      $sql = "SELECT * FROM `account` WHERE `email`='".$email."'";
      $db_login->query($sql);

          if($db_login->count() > 0){
              $errors[] = "That Email is Already in Use. Please try Another one";
          }

  }
	return $errors;
}

//---------------------------------------------------------------------------
//-- User Helpers
//---------------------------------------------------------------------------
function userid_by_email($email){
	global $config, $db_login;
	$sql = "SELECT `id` FROM `account` WHERE `email`='$email'";
	$db_login->query($sql);
	if($db_login->count() > 0){
		$row=$db_login->fetchRow();
		return $row;
	} else {
		return false;
	}
}

//---------------------------------------------------------------------------
//-- LIVE STREAM FUNCTIONS
//---------------------------------------------------------------------------
function getLiveStreams(){
	global $config, $db_web;
	$sql = "SELECT * FROM `livestream`;";
	$db_web->query($sql);
	
	$livestreams = array();
	
	if($db_web->count() > 0){
		while($row=$db_web->fetchRow()){
			$livestreams[] = $row;
		}
	}
	return $livestreams;
}

function countLiveStreams(){
	global $config, $db_web;
	$sql = "SELECT * FROM `livestream`;";
	$db_web->query($sql);
	
	return $db_web->count();
}

function addLiveStream($url, $user, $title, $content){
	global $config, $db_web;
	$sql = "INSERT INTO `livestream`
            (`url`,`user`,`title`,`content`)
           	VALUES ('".$url."','".$this->userid."','".$title."','".$content."')";
	$db_web->query($sql);
	return true;
}

//---------------------------------------------------------------------------
//-- Mail Helpers
//---------------------------------------------------------------------------
function send_mail($tpl, $to, $subject, $data){
	global $config, $twig;
	$header = 'From: ' . $config['mail']['from'] . "\r\n" .
	    			'Reply-To: ' . $config['mail']['reply'] . "\r\n" .
	    			'X-Mailer: PHP/' . phpversion();
	$tpl = $twig->loadTemplate($tpl.'.mail.tpl');
	$text = $tpl->render($data);
	return mail($to,$subject,$text,$header);
}

//---------------------------------------------------------------------------
//-- AJAX Functions
//---------------------------------------------------------------------------
function return_ajax($status,$msg=""){
	$return['status'] = $status;
	$return['msg'] = $msg;
	echo json_encode($return);
}

//---------------------------------------------------------------------------
//-- Misc Helper Functions
//---------------------------------------------------------------------------
function protect($string){
	global $db_web;
	return $db_web->escape_string($string);
}

function root_url() {
	global $config;
 	$pageURL = 'http';
 	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 	$pageURL .= "://";
 	if ($_SERVER["SERVER_PORT"] != "80") {
  	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$config['root_base'];
 	} else {
  	$pageURL .= $_SERVER["SERVER_NAME"].$config['root_base'];
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