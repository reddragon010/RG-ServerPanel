<?php
require_once('common.php');

if(isset($_POST['password']) && isset($_POST['password_confirm'])){
	if($_POST['password'] == $_POST['password_confirm']){
		if($user->change_password($_POST['password'])){
			return_ajax('success','Passwort erfolgreich geändert');
		} else {
			return_ajax('error','Passwort konnte nicht geändert werden');
		}
	} else {
		return_ajax('error','Passwörter müssen übereinstimmen');
	}
} else {
	$tpl = $twig->loadTemplate('change_password.tpl');
	echo $tpl->render(array());
}
?>