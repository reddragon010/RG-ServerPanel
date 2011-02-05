<?php
require_once('common.php');

if(isset($_POST['email']) && isset($_POST['email_confirm'])){
	if($_POST['email'] == $_POST['email_confirm']){
		if($user->change_email($_POST['email'])){
			return_ajax('success','E-Mail Adresse erfolgreich geändert');
		} else {
			return_ajax('error','E-Mail Adresse konnte nicht geändert werden');
		}
	} else {
		return_ajax('error','E-Mail Adresse müssen übereinstimmen');
	}
} else {
	$tpl = $twig->loadTemplate('change_email.tpl');
	echo $tpl->render(array());
}
?>