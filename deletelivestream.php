<?php
require_once('common.php');

if(isset($_POST['stream_url']) && isset($_POST['stream_title']) && isset($_POST['stream_description'])){
	if(!empty($_POST['stream_url']) && !empty($_POST['stream_title']) && !empty($_POST['stream_description'])){

		$url = $_POST['stream_url'];
		$username = "userX";
		$title = $_POST['stream_title'];
		$content = $_POST['stream_description'];
		
		if(addLiveStream($url, $username, $title, $content)){
			return_ajax('success',"Erfolgreich!");
		} else {
			return_ajax('error',"Fehler!, versuchen sie es erneut!");	
		}
	} else{
		return_ajax('error',"URL, Titel oder Beschreibung wurden nicht angegeben!");
	}
} else {
$tpl = $twig->loadTemplate('addLiveStream.tpl');
echo $tpl->render(array());
}
?> 