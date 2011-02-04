<?php
require_once('common.php');

if(isset($_POST['login_username']) && isset($_POST['login_password'])){
	if(!empty($_POST['login_username']) && !empty($_POST['login_password'])){
		$user = new User;
		if($user->login($_POST['login_username'],$_POST['login_password'])){
			echo "Benutzername/Passwort nicht existent oder inkorrekt!";
		} else {
			echo "Benutzername/Passwort nicht existent oder inkorrekt!";
			header("HTTP/1.0 404 Not Found");  	
		}
	} else{
		echo "Name oder Passwort wurden nicht angegeben!";
		header("HTTP/1.0 404 Not Found");
	}
} else {
$tpl = $twig->loadTemplate('login_form.tpl');
echo $tpl->render(array());
}
?> 