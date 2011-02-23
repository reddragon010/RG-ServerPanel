<?php 
	//---------------------------------------------------------------------------
	//-- Bootstraps
	//---------------------------------------------------------------------------
	//-- Config
	require_once('config/config.php');
	
	//--Autoloader
	require_once $config['server_root'].'/lib/autoloader.php';
	Autoloader::register();
	
	//-- Loaders
	require_once('loaders/defaults.php');
	require_once('loaders/realms.php');
	require_once('loaders/sessions.php');
	require_once('loaders/template_system.php');
	
	//-- Helpers
	require_once('helpers/template.php');
	require_once('helpers/application.php');
	
	//-- Lang
	require_once("lang/{$config['lang']}/lang.php");
	
	//---------------------------------------------------------------------------
	//-- Routing
	//---------------------------------------------------------------------------
	$params = $_GET + $_POST;
	unset($params['controller']);
	unset($params['action']);
	
	$request['controller'] = 'news';
	$request['action'] = 'index';
	
	if(isset($_GET['controller'])){
		$request['controller'] = $_GET['controller'];
	}
	if(isset($_GET['action'])){
		$request['action'] = $_GET['action'];
	}
	$controller_name = $request['controller'] . '_controller';
	$controller = new $controller_name;
	
	call_user_func_array(array($controller, $request['action']),array($params));