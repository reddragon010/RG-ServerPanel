<?php
$env = getenv('ENV_NAME');
if (empty($env)) {
    $env = 'default';
}
Environment::setup($env);
if (Environment::get_config_value('debug')) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
