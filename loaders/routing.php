<?php
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
$app_controller = new application_controller;

if(method_exists($app_controller, 'on_each_request')){
	$app_controller->on_each_request();
}

call_user_func_array(array($controller, $request['action']),array($params));