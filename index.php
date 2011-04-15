<?php 
  //---------------------------------------------------------------------------
  //-- Set Basics
  //---------------------------------------------------------------------------
  define('SERVER_ROOT', getcwd());
    
	//---------------------------------------------------------------------------
	//-- Bootstraps
	//---------------------------------------------------------------------------
	//-- Config
	require_once('config/config.php');
	
	//--Autoloader
	require_once('lib/autoloader.php');
	Autoloader::register();
	
	//-- Loaders
	require_once('loaders/defaults.php');
	require_once('loaders/db_connections.php');
	require_once('loaders/sessions.php');
	
	//-- Helpers
	require_once('helpers/application.php');
	
	//-- Lang
	require_once("lang/{$config['lang']}/lang.php");
	
	//---------------------------------------------------------------------------
	//-- Routing
	//---------------------------------------------------------------------------
	$rawRequest = explode('/', $_REQUEST['url']);
	
	if(!isset($rawRequest[0])){
		$rawRequest[0] = 'news';
	}
	if(!isset($rawRequest[1])){
		$rawRequest[1] = 'index';
	}
	 
	$request['controller'] = $rawRequest[0];
	$request['action'] = $rawRequest[1];
	
	$params = $_GET + $_POST;
	
	$controller_name = $request['controller'] . '_controller';
	$controller = new $controller_name;
	
	call_user_func_array(array($controller, $request['action']),array($params));
