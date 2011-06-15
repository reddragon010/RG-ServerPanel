<?php

if (isset($_REQUEST['url'])) {
    if (substr($_REQUEST['url'], 0, 1) !== '/') {
        $_REQUEST['url'] = '/' . $_REQUEST['url'];
    }

    $rawRequest = explode('/', $_REQUEST['url']);
} else {
    $rawRequest = array();
}

if (empty($rawRequest[1])) {
    $rawRequest[1] = 'home';
}
if (empty($rawRequest[2])) {
    $rawRequest[2] = 'index';
}

$request['controller'] = $rawRequest[1];
$request['action'] = $rawRequest[2];

if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "") {
    $domain = parse_url($_SERVER['HTTP_REFERER']);
    if ($domain['host'] == Environment::$app_host) {
        $request['ref'] = $_SERVER['HTTP_REFERER'];
    } else {
        $request['ref'] = Environment::$app_url;
    }
} else {
    $request['ref'] = Environment::$app_url;
}

$params = $_GET + $_POST;

$controller_name = ucfirst($request['controller']) . 'Controller';

$app_controller = new ApplicationController();

if (class_exists($controller_name)) {
    $controller = new $controller_name;
} else {
    $app_controller->set_header_status(404);
    $app_controller->render("404");
    die();
}

if (isset($app_controller->before_all) && !empty($app_controller->before_all)) {
    foreach ($app_controller->before_all as $call) {
        $app_controller->$call();
    }
}

if (isset($controller->before) && !empty($controller->before)) {
    foreach ($controller->before as $call) {
        $controller->$call();
    }
}

call_user_func_array(array($controller, $request['action']), array($params));