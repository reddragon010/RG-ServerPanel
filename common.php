<?php
//Protect files from hacking
//if(!defined('IN_THE_BOX'))
//	die('BlaBliBlub');

//defaults
$CLASSES 	= array(1 => 'warrior', 
									2 => 'paladin', 
									3 => 'hunter', 
									4 => 'rogue', 
									5 => 'priest', 
									6 => 'dk', 
									7 => 'shaman', 
									8 => 'mage', 
									9 => 'warlock', 
									11 => 'druid');
$RACES 		= array(1 => 'human', 
									2 => 'orc', 
									3 => 'dwarf', 
									4 => 'nightelf', 
									5 => 'undead', 
									6 => 'tauren', 
									7 => 'gnome', 
									8 => 'troll', 
									10 => 'bloodelf', 
									11 => 'draenei');
$GENDERS 	= array(1 => 'male', 
									2 => 'female');
$ALLIANCE = array(1, 3, 4, 7, 11);
$HORDE 		= array(2, 5, 6, 8, 10);
$MAPS 		= array(0 => 'eastern kindoms', 
									1 => 'kalimdor', 
									530 => 'outland', 
									571 => 'northrend', 
									603 => 'northrend');

//require important files
require_once(__DIR__ . '/include/config.php');
require_once(__DIR__ . '/include/functions.php');
require_once(__DIR__ . '/include/database.class.php');
require_once(__DIR__ . '/include/character.class.php');
require_once(__DIR__ . '/include/user.class.php');

//loading database
$db_chars = new Database($config,$config['db']['chardb']);
$db_web = new Database($config,$config['db']['webdb']);
$db_realm = new Database($config,$config['db']['realmdb']);

//load user object
if(!isset($user))
	$user = new User;

//load template system
require_once 'include/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(array(__DIR__.'/templates',__DIR__.'/templates/mail'));
if($config['cache']) {$cache = __DIR__ . '/cache';} else {$cache = false;}
$twig = new Twig_Environment($loader, array(
  'cache' => $cache,
	'debug' => $config['debug'],
));

$twig->addFunction('logged_in', 						new Twig_Function_Function('logged_in'));
$twig->addFunction('flushflash', 						new Twig_Function_Function('flushflash'));
$twig->addFunction('getServerStatus', 			new Twig_Function_Function('getServerStatus'));
$twig->addFunction('getServerUptime', 			new Twig_Function_Function('getServerUptime'));
$twig->addFunction('getPlayersOnlineCount', new Twig_Function_Function('getPlayersOnlineCount'));
$twig->addFunction('display_avatar', 				new Twig_Function_Function('display_avatar'));
$twig->addFunction('display_money', 				new Twig_Function_Function('display_money'));
$twig->addFunction('class_name', 						new Twig_Function_Function('class_name'));
$twig->addFunction('race_name', 						new Twig_Function_Function('race_Name'));
$twig->addFunction('map_name', 							new Twig_Function_Function('map_name'));
$twig->addFunction('gender_name', 					new Twig_Function_Function('gender_name'));
$twig->addFunction('zone_name', 						new Twig_Function_Function('zone_name'));

$twig->addGlobal('user', $user);
?>