<?php

if (isset($_REQUEST['url'])) {
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

$params = $_GET + $_POST;

$controller_name = $request['controller'] . '_controller';
$controller = new $controller_name;

if (isset($controller->before_all) && !empty($controller->before_all)) {
    foreach ($controller->before_all as $call) {
        $controller->$call();
    }
}

if (isset($controller->before) && !empty($controller->before)) {
    foreach ($controller->before as $call) {
        $controller->$call();
    }
}

call_user_func_array(array($controller, $request['action']), array($params));