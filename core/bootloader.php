<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of StupidPrlf.
 *
 *    StupidPrlf is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    StupidPrlf is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with StupidPrlf.  If not, see <http://www.gnu.org/licenses/>.
 */

//---------------------------------------------------------------------------
//-- Bootstraping
//---------------------------------------------------------------------------

//-- Registering Framework_Autoloader and Custom Autoloaders
if (RUNLEVEL >= 1) {
    require_once(FRAMEWORK_ROOT . '/core/autoloader.php');
    Framework_Autoloader::register();
    require_once(FRAMEWORK_ROOT . '/loaders/custom_libs.php');
}

//-- Setting up the Environment, Error-Handling and Databases
if (RUNLEVEL >= 2) {
    require_once(FRAMEWORK_ROOT . '/loaders/environment.php');
    require_once(FRAMEWORK_ROOT . '/loaders/loggers.php');
    GenericLogger::enter_group('Runlevel 2');
    GenericLogger::debug('Environment & Loggers loaded');
    require_once(FRAMEWORK_ROOT . '/loaders/error_handling.php');
    GenericLogger::debug('Error Handling loaded');
    require_once(FRAMEWORK_ROOT . '/loaders/databases.php');
    GenericLogger::debug('Databases Loaded');
    GenericLogger::leave_group();
}

//-- Start Session-Management
if (RUNLEVEL >= 3) {
    GenericLogger::enter_group('Runlevel 3');
    GenericLogger::debug('Loading SessionManagement');
    require_once(FRAMEWORK_ROOT . '/loaders/sessions.php');
    SessionManager::start();
    GenericLogger::leave_group();
}

//-- Loading Application-Variables
if (RUNLEVEL >= 4) {
    GenericLogger::enter_group('Runlevel 4');
    GenericLogger::debug('Loading Application-Variables');
    require_once(APP_ROOT . '/defaults.php');
    i18n::load();
    GenericLogger::leave_group();
}

//-- Startup Application
if (RUNLEVEL >= 5) {
    GenericLogger::enter_group('Runlevel 5');
    GenericLogger::debug('Starting Application');
    Kernel::init();
    GenericLogger::leave_group();
}
