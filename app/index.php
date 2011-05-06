<?php 

	if(!defined('RUNLEVEL'))
		define('RUNLEVEL', 5);
	//---------------------------------------------------------------------------
	//-- Bootstraping
	//---------------------------------------------------------------------------

	if(SHOW_ERRORS){
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
	}

	//-- Loading basic Environment-Variables
	if(RUNLEVEL >= 1){
		require_once('../basics.php');
	}
	
	//-- Registering Autoloader
	if(RUNLEVEL >= 2){
		require_once('../lib/autoloader.php');
		Autoloader::register();
	}
	
	//-- Setting up the Environment
	if(RUNLEVEL >= 3){
		require_once('../loaders/environment.php');
	}
	
	//-- Loading Application-Variables
	if(RUNLEVEL >= 4){
		require_once('defaults.php');
		$lang = Environment::get_config_value('lang');
		require_once("lang/{$lang}/lang.php");
	}
	
	//-- Startup Application
	if(RUNLEVEL >= 5){
		session_start();
		require_once("../loaders/routing.php");
	}
	