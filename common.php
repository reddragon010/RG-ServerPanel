<?php
//Protect files from hacking
//if(!defined('IN_THE_BOX'))
//	die('BlaBliBlub');

//require important files
require_once(__DIR__ . '/include/config.php');
require_once(__DIR__ . '/include/functions.php');
require_once(__DIR__ . '/include/database.class.php');
require_once(__DIR__ . '/include/character.class.php');
require_once(__DIR__ . '/include/user.class.php');

//load user object
if(!isset($user))
	$user = new User;

//load template system
require_once 'include/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
if($config['cache']) {$cache = __DIR__ . '/cache';} else {$cache = false;}
$twig = new Twig_Environment($loader, array(
  'cache' => $cache,
	'debug' => $config['debug'],
));

$twig->addFunction('logged_in', new Twig_Function_Function('logged_in'));
$twig->addFunction('flushflash', new Twig_Function_Function('flushflash'));
$twig->addFunction('getServerStatus', new Twig_Function_Function('getServerStatus'));
$twig->addFunction('getServerUptime', new Twig_Function_Function('getServerUptime'));
$twig->addFunction('getPlayersOnlineCount', new Twig_Function_Function('getPlayersOnlineCount'));
$twig->addFunction('display_avatar', new Twig_Function_Function('display_avatar'));
$twig->addFunction('display_money', new Twig_Function_Function('display_money'));

$twig->addGlobal('user', $user);
?>