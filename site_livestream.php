<?php
	require_once('common.php');
	$tpl = $twig->loadTemplate('site_livestream.tpl');
	echo $tpl->render(array());
?>