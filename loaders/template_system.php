<?php
require_once $config['server_root'].'/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

//-- Loading Template Files
$loader = new Twig_Loader_Filesystem(array(
	$config['server_root']."/themes/{$config['theme']}/templates",
	$config['server_root']."/themes/{$config['theme']}/templates/mails",
	$config['server_root']."/themes/{$config['theme']}/templates/forms"
));

//-- Setting Template-System Config
if($config['cache']) {$cache = $config['server_root'] . '/cache/templates';} else {$cache = false;}
$twig = new Twig_Environment($loader, array(
  'cache' => $cache,
	'debug' => $config['debug'],
));