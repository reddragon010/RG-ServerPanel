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

namespace Dreamblaze\Framework\Core;

class Bootloader {

    public static function boot(){
        if (RUNLEVEL >= 1)
            self::runlevel1();

        if (RUNLEVEL >= 2)
            self::runlevel2();

        if (RUNLEVEL >= 3)
            self::runlevel3();

        if (RUNLEVEL >= 4) {
            self::runlevel4();
        }
    }

    public static function finish(){
        if (RUNLEVEL >= 3){
            $sm = SessionManager::get_instance();
            $sm->close();
        }

        Logger::end();

    }

    private static function runlevel1(){
        Environment::setup();
    }

    private static function runlevel2(){
        Logger::setup();
        Logger::enter_group('Runlevel 2');
        Logger::debug('Loggers loaded');
        Databases::setup();
        Logger::debug('Databases Loaded');
        SessionManager::start();
        Logger::debug('Session-Management Loaded');
        Logger::leave_group();
    }

    private static function runlevel3(){
        Logger::enter_group('Runlevel 4');
        Logger::debug('Loading Application-Variables');
        require_once(APP_ROOT . '/defaults.php');
        I18n::load();
        Logger::leave_group();
    }

    private static function runlevel4(){
        Logger::enter_group('Runlevel 5');
        Logger::debug('Starting Application');
        Kernel::init();
        Logger::leave_group();
    }
}