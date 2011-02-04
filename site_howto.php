<?php
	require_once('common.php');
	$tpl = $twig->loadTemplate('site_howto.tpl');
	echo $tpl->render(array());
?>