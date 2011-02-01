<?php
if(!defined('IN_THE_BOX'))
	die('BlaBliBlub');
require_once(dirname(__FILE__) . '/db.class.php');
require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/functions.php');
require_once(dirname(__FILE__) . '/user.class.php');
if(!isset($user))
	$user = new User; 
?>