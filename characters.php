<?php
require_once('common.php');

if($user->logged_in()){
	$user->fetchChars();
	$main = $user->fetchMainChar();
	$tpl = $twig->loadTemplate('my_characters.tpl');
	echo $tpl->render(array('main' => $main));
} 
?>