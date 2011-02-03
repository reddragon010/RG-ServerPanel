<?php
require_once('common.php');

if(isset($_POST['email'])){
	if(!empty($_POST['email']) && $user->userid == NULL){
		$user_id = userid_by_email($_POST['email']);
		if($user_id){
			$user = new User;
			$user->loadUser($user_id,false);
			if($user->send_reset_password()){
				flash('success', 'E-Mail wurde verschickt');
			} else {
				flash('error', 'E-Mail konnte nicht gesendet werden');
			}
		} else {
			flash('error', 'E-Mail Adresse konnte nicht gefunden werden');
		}
	} else {
		flash('error', 'Du bist eingeloggt!? Wie kann man da sein Passwort verlieren??');
		header('Location: index.php');
	}
}
$tpl = $twig->loadTemplate('password_lost.tpl');
echo $tpl->render(array());
?>