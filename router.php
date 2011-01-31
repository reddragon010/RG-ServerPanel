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
		case 'logout':
			$content='home.php';
			$user->logout();
			//header('Location: index.php');
			break;
		default: 
			$content='home.php'; 
			break;
	}
} else {
	$content='home.php';
}
?>