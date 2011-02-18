<?php
//Protect files from hacking
//if(!defined('IN_THE_BOX'))
//	die('BlaBliBlub');

//---------------------------------------------------------------------------
//-- Default Vars
//---------------------------------------------------------------------------
$CLASSES 	= array(
	1 => 	'warrior', 
	2 =>	'paladin', 
	3 => 	'hunter', 
	4 => 	'rogue', 
	5 => 	'priest', 
	6 => 	'dk', 
	7 => 	'shaman', 
	8 => 	'mage', 
	9 => 	'warlock', 
	11 => 'druid'
);
$RACES 		= array(
	1 => 	'human', 
	2 => 	'orc', 
	3 => 	'dwarf', 
	4 => 	'nightelf', 
	5 => 	'undead', 
	6 => 	'tauren', 
	7 => 	'gnome', 
	8 => 	'troll', 
	10 => 'bloodelf', 
	11 => 'draenei'
);
$GENDERS 	= array(
	1 => 'male', 
	2 => 'female'
);
$ALLIANCE = array(1, 3, 4, 7, 11);
$HORDE 		= array(2, 5, 6, 8, 10);
$MAPS 		= array(
	-1	=>	'unknown',	
	0 	=> 	'eastern_kindoms', 
	1		=> 	'kalimdor', 
	530 => 	'outland', 
	571 => 	'northrend', 
	603 => 	'northrend'
);
$FACTIONS = array(
	0 => 'alliance',
	1 => 'horde',
	2	=> 'gms'
);
$WEEKDAYS = array(
	"Sonntag",
	"Montag",
	"Dienstag",
	"Mittwoch",
	"Donnerstag",
	"Freitag",
	"Samstag"
);
$STATUS = array(
	0 => 'geschlossen',
	1 => 'interne Tests',
	2 => 'offene Tests',
	3 => 'offen'
);

//---------------------------------------------------------------------------
//-- Require All Important Files
//---------------------------------------------------------------------------

//-- Config
require_once(__DIR__ . '/include/config.php');

//-- Lang
require_once(__DIR__ . "/lang/{$config['lang']}/lang.php");

//-- Functions
require_once(__DIR__ . '/include/functions.php');
require_once(__DIR__ . '/include/functions_template.php');

//-- Classes
require_once(__DIR__ . '/include/database.class.php');
require_once(__DIR__ . '/include/character.class.php');
require_once(__DIR__ . '/include/user.class.php');
require_once(__DIR__ . '/include/realm.class.php');

//-- RSS Parser
require_once(__DIR__ . '/include/simplepie.class.php');

//---------------------------------------------------------------------------
//-- Loading Database Objects
//---------------------------------------------------------------------------
$db_web 	= new Database($config['web']);
$db_login = new Database($config['login']);

//---------------------------------------------------------------------------
//-- loading User Object
//---------------------------------------------------------------------------
if(!isset($user))
	$user = new User;

//---------------------------------------------------------------------------
//-- loading Realm Objects
//---------------------------------------------------------------------------
foreach($config['realm'] as $realm_id => $realm){
	$realms[$realm_id] = new Realm($realm_id);
}

//---------------------------------------------------------------------------
//-- Loading Template System
//---------------------------------------------------------------------------
require_once 'include/Twig/Autoloader.php';
Twig_Autoloader::register();

//-- Loading Template Files
$loader = new Twig_Loader_Filesystem(array(
	__DIR__."/themes/{$config['theme']}/templates",
	__DIR__."/themes/{$config['theme']}/templates/mails",
	__DIR__."/themes/{$config['theme']}/templates/forms"
));

//-- Setting Template-System Config
if($config['cache']) {$cache = __DIR__ . '/cache';} else {$cache = false;}
$twig = new Twig_Environment($loader, array(
  'cache' => $cache,
	'debug' => $config['debug'],
));

//-- Register Custom Functions
$twig->addFunction('flushflash', 					new Twig_Function_Function('flushflash'));
$twig->addFunction('selectArray', 				new Twig_Function_Function('selectArray', array('is_safe' => array('html'))));
$twig->addFunction('progress_bar', 				new Twig_Function_Function('progress_bar', array('is_safe' => array('html'))));

//-- Register Custom Filters
//- Char 
$twig->addFilter('avatar', 								new Twig_Filter_Function('avatar', array('is_safe' => array('html'))));
$twig->addFilter('money', 								new Twig_Filter_Function('money', array('is_safe' => array('html'))));
$twig->addFilter('class_icon', 						new Twig_Filter_Function('class_icon', array('is_safe' => array('html'))));
$twig->addFilter('race_icon', 						new Twig_Filter_Function('race_icon', array('is_safe' => array('html'))));
$twig->addFilter('faction_icon', 					new Twig_Filter_Function('faction_icon', array('is_safe' => array('html'))));
$twig->addFilter('map_name', 							new Twig_Filter_Function('map_name'));
$twig->addFilter('gender_name', 					new Twig_Filter_Function('gender_name'));
$twig->addFilter('zone_name', 						new Twig_Filter_Function('zone_name'));
//- Server                                           
$twig->addFilter('uptime',								new Twig_Filter_Function('uptime'));
$twig->addFilter('online',								new Twig_Filter_Function('online', array('is_safe' => array('html'))));
//- RepoTracker
$twig->addFilter('time_ago',							new Twig_Filter_Function('time_ago'));
$twig->addFilter('format_author',					new Twig_Filter_Function('format_author'));
$twig->addFilter('format_repo',						new Twig_Filter_Function('format_repo'));
//- IniTests
$twig->addFilter('boss_icon',							new Twig_Filter_Function('boss_icon', array('is_safe' => array('html'))));

//-- Register Custom Globals
$twig->addGlobal('user', $user);
$twig->addGlobal('realms', $realms);
$twig->addGlobal('STATUS', $STATUS);
$twig->addGlobal('root_url', $config['root_url']);
$twig->addGlobal('theme_url', $config['root_url'] . '/themes/' . $config['theme']);
?>