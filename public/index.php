<?php

if (!defined('RUNLEVEL'))
    define('RUNLEVEL', 5);
//---------------------------------------------------------------------------
//-- Bootstraping
//---------------------------------------------------------------------------
//-- Loading basic System-Variables
require_once(__DIR__ . '/../basics.php');

if (SHOW_ERRORS) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

//-- Registering Framework_Autoloader and Custom Autoloaders
if (RUNLEVEL >= 1) {
    require_once(FRAMEWORK_ROOT . '/core/autoloader.php');
    Framework_Autoloader::register();
    require_once(FRAMEWORK_ROOT . '/loaders/custom_libs.php');
}

//-- Setting up the Environment and Error-Handling
if (RUNLEVEL >= 2) {
    require_once(FRAMEWORK_ROOT . '/loaders/error_handling.php');
    require_once(FRAMEWORK_ROOT . '/loaders/environment.php');
}

//-- Loading Application-Variables
if (RUNLEVEL >= 3) {
    require_once(APP_ROOT . '/defaults.php');
    $lang = Environment::get_config_value('lang');
    require_once(APP_ROOT . "/lang/{$lang}/lang.php");
}

//-- Startup Application
if (RUNLEVEL >= 4) {
    session_start();
    require_once(FRAMEWORK_ROOT . "/loaders/routing.php");
}