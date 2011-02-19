<?php
	require_once('common.php');
	
	if(isset($_GET['id'])){
		if(!empty($_GET['id'])){
	
			$id = $_GET['id'];
			
			if(deleteLiveStream($id)){
				flash("success","Erfolgreich!");
			} else {
				flash("error","LiveStream konnte nicht gelöscht werden!");
			}
		} else{
			flash("error","ID wurde nicht angegeben!");
		}
	}
	
	$count = countLiveStreams();
	$livestreams = getLiveStreams();
	
	$tpl = $twig->loadTemplate('site_livestream.tpl');
	echo $tpl->render(array(
	'count' => $count,
	'livestreams' => $livestreams
	));
?>