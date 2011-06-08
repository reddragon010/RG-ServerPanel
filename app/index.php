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

//-- Registering Autoloader
if (RUNLEVEL >= 1) {
    require_once(__DIR__ . '/../lib/autoloader.php');
    Autoloader::register();
}

//-- Setting up the Environment and Error-Handling
if (RUNLEVEL >= 2) {
    require_once(__DIR__ . '/../loaders/error_handling.php');
    require_once(__DIR__ . '/../loaders/environment.php');
}

//-- Loading Application-Variables
if (RUNLEVEL >= 3) {
    require_once('defaults.php');
    $lang = Environment::get_config_value('lang');
    require_once("lang/{$lang}/lang.php");
}

//-- Startup Application
if (RUNLEVEL >= 4) {
    session_start();
    require_once(__DIR__ . "/../loaders/routing.php");
}