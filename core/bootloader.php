<?php
//---------------------------------------------------------------------------
//-- Bootstraping
//---------------------------------------------------------------------------

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
    Debug::add('Environment loaded');
}

//-- Start Session-Management
if (RUNLEVEL >= 3) {
    Debug::add('Loading SessionManagement');
    require_once(FRAMEWORK_ROOT . '/loaders/sessions.php');
    SessionManager::start();
}

//-- Loading Application-Variables
if (RUNLEVEL >= 4) {
    Debug::add('Loading Application-Variables');
    require_once(APP_ROOT . '/defaults.php');
    i18n::load();
    if(Environment::get_value('debug')){
        Debug::setup();
    }
}

//-- Startup Application
if (RUNLEVEL >= 5) {
    Debug::add('Starting Application');
    $router = Router::instance();
    $router->route();
}
