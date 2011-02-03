<?php
require_once('common.php');

if(isset($_GET['key'])) {
	if(User::validate_reset_password_key($_GET['key'])){
		$tpl = $twig->loadTemplate('password_reset.tpl');
		echo $tpl->render(array('key' => $_GET['key']));
	} 
} elseif(isset($_POST['password']) && isset($_POST['password_confirm'])) {
	if($_POST['password'] == $_POST['password_confirm']){
		if(User::reset_password($_POST['key'], $_POST['password'])){
			flash('success', 'Passwort wurde erfolgreich geändert! Du kannst dich jetzt einloggen');
		} else {
			flash('error','Der Key ist ungültig!');
		}
		header('Location: index.php');
	} else {
		flash('error', 'die Passwörter müssen gleich sein!');
	}
} else {
	header('Location: index.php');
}
$tpl = $twig->loadTemplate('password_reset.tpl');
echo $tpl->render(array('key' => $_GET['key']));
?>