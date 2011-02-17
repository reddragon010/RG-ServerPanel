<?php
	require_once('common.php');
	
	$count = countLiveStreams();
	$livestreams = getLiveStreams();
	
	$tpl = $twig->loadTemplate('site_livestream.tpl');
	echo $tpl->render(array(
	'count' => $count,
	'id' => $livestreams
	));
?>