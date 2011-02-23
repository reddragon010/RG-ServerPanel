<?php
require_once('common.php');

$feed = new SimplePie();
$feed->set_feed_url($config['repos']);
$feed->enable_order_by_date(true);
$success = $feed->init();
$feed->handle_content_type();

if($success){
	$tpl = $twig->loadTemplate('tool_repotracker.tpl');
	echo $tpl->render(array('feed' => $feed));
}
?>