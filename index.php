<?php 
	//---------------------------------------------------------------------------
	//-- Bootstraps
	//---------------------------------------------------------------------------
	
	//-- Framework
	require_once('config/config.php');
	
	require_once('lib/basics.php');
	
	require_once('lib/autoloader.php');
	Autoloader::register();
	
	require_once('lib/Twig/Autoloader.php');
	
	require_once('loaders/defaults.php');
	require_once('loaders/db_connections.php');
	require_once('loaders/sessions.php');
	
	//-- Application
	require_once('app/helpers/application.php');
	
	require_once("app/lang/{$config['lang']}/lang.php");
	
	//---------------------------------------------------------------------------
	//-- Routing
	//---------------------------------------------------------------------------
	if(isset($_REQUEST['url'])){
		$rawRequest = explode('/', $_REQUEST['url']);
	} else {
		$rawRequest = array();
	}

	if(empty($rawRequest[0])){
		$rawRequest[0] = 'home';
	}
	if(empty($rawRequest[1])){
		$rawRequest[1] = 'index';
	}
	 
	$request['controller'] = $rawRequest[0];
	$request['action'] = $rawRequest[1];
	
	$params = $_GET + $_POST;
	
	$controller_name = $request['controller'] . '_controller';
	$controller = new $controller_name;
	
	call_user_func_array(array($controller, $request['action']),array($params));
