<?php
require_once('common.php');

if(isset($_GET['stream'])){
	if(!empty($_GET['stream'])){
		$url = $_GET['stream'];
		$tpl = $twig->loadTemplate('showLiveStream.tpl');
		echo $tpl->render(array(
								'url' => $url,
								));
	}
}

?> 