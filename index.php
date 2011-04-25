<?php 
	//---------------------------------------------------------------------------
	//-- Bootstraps
	//---------------------------------------------------------------------------
	session_start();
	
	//-- Framework
	require_once('config/config.php');
	
	require_once('lib/basics.php');
	
	require_once('lib/autoloader.php');
	Autoloader::register();
	
	require_once('loaders/db_connections.php');
	
	//-- Application
	require_once('app/defaults.php');
	require_once("app/lang/{$config['lang']}/lang.php");
	
	//-- Frontend
	require_once("loaders/routing.php");
	