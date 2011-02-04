<?php
	require_once('common.php');
	$tpl = $twig->loadTemplate('howto.tpl');
	echo $tpl->render(array());
?>