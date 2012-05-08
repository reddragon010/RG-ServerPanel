<?php
//-- For Bootlevel-Description see core/bootloader.php
if (!defined('RUNLEVEL'))
    define('RUNLEVEL', 5);

//-- Loading basic System-Variables
require_once(__DIR__ . '/../basics.php');

require_once(LIB_ROOT . '/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php');
require_once(FRAMEWORK_ROOT . '/ClassLoader.php');
ClassLoader::startup();

//-- Booting the Framework
\Dreamblaze\Framework\Core\Bootloader::boot();

// -- Custom Pre-Processing can be done (only!!) here --

// -----------------------------------------------------

\Dreamblaze\Framework\Core\Bootloader::finish();