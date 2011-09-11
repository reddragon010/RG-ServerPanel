<?php
//-- For Bootlevel-Description see core/bootloader.php
if (!defined('RUNLEVEL'))
    define('RUNLEVEL', 5);

//-- Loading basic System-Variables
require_once(__DIR__ . '/../basics.php');

//-- Booting the Framework
require_once(FRAMEWORK_ROOT . '/core/bootloader.php');

// -- Custom Pre-Processing can be done (only!!) here --
//var_dump(Debug::getDebugBuffer());
// -----------------------------------------------------

//-- Session Cleanup (if sessions are loaded)
if (RUNLEVEL >= 3){
    $session = SessionManager::get_instance();
    $session->close();
}