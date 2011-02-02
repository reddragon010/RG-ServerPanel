<?php
require_once('common.php');

if(isset($_GET['guid']) && $user->setMainChar($_GET['guid'])){
	flash('success', "Main wurde geändert");
} else {
  flash('error', "Main konnte nicht geändert werden");
}
header('Location: my_characters.php');
?>