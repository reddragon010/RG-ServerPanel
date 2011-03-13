<?php
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
//-- Misc Helper Functions
//---------------------------------------------------------------------------
function protect($string){
	global $config, $dbs;
	$db = $dbs['web'];
	return $db->escape($string);
}

function rooturl() {
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