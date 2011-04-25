<?php
define('FRAMEWORK_ROOT', getcwd());
define('APP_ROOT', FRAMEWORK_ROOT . '/app');
define('APP_URL', rooturl() . $config['app_url_base']);

function rooturl() {
	global $config;
 	$pageURL = 'http';

 	if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") 
		$pageURL .= "s";
		
 	$pageURL .= "://";

 	if($_SERVER["SERVER_PORT"] != "80") {
  	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$config['app_url_base'];
 	} else {
  	$pageURL .= $_SERVER["SERVER_NAME"].$config['root_base'];
 	}

 	return $pageURL;
}