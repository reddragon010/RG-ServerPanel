<?php
if(isset($_GET['a'])){ 
	switch($_GET['a']){
		case 'my_characters': 
			$content='my_characters.php'; 
			break;
		case 'make_main': 
			$content='my_characters.php';
			if(isset($_GET['guid']) && $user->setMainChar($_GET['guid'])){
				flash('success', "Main wurde geändert");
			} else {
			  flash('error', "Main konnte nicht geändert werden");
			}
			break;
		case 'accept_invite':
			if(isset($_GET['token'])){
				if(!empty($user)){
					$user->use_friend_token($_GET['token']);
					$content='my_friends.php';
				} else {
					flush('error','Bitte einloggen und noch einmal versuchen!');
				}
			} else {
				Header('Location: index.php');
			}
			break;
		case 'my_friends':
			$content='my_friends.php';
			break;
		case 'friend_invite':
			$content='form_invite.php';
			break;
		case 'logout':
			$content='home.php';
			$user->logout();
			break;
		default: 
			$content='home.php'; 
			break;
	}
} else {
	$content='home.php';
}
?>