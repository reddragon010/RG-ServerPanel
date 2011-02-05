<?php
require_once('common.php');

if(isset($_POST['login_username']) && isset($_POST['login_password'])){
	if(!empty($_POST['login_username']) && !empty($_POST['login_password'])){
		$user = new User;
		if($user->login($_POST['login_username'],$_POST['login_password'])){
			return_ajax('success',"Login erfolgreich!");
		} else {
			return_ajax('error',"Benutzername/Passwort nicht existent oder inkorrekt!");	
		}
	} else{
		return_ajax('error',"Name oder Passwort wurden nicht angegeben!");
	}
} else {
$tpl = $twig->loadTemplate('login.tpl');
echo $tpl->render(array());
}
?> 