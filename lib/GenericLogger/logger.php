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

class GenericLogger {
    private static $observers = array();

    const TYPE_DEBUG = 4;
    const TYPE_NOTICE = 3;
    const TYPE_WARNING = 2;
    const TYPE_ERROR = 1;

    public static function register_observer($observer){
        self::$observers[get_class($observer)] = $observer;
    }

    public static function get_observer($observer_class_name){
        if(isset(self::$observers[$observer_class_name]))
            return self::$observers[$observer_class_name];
        else
            return null;
    }

    private static function trigger_event($event /*, argument1, argument2 */){
        $params = func_get_args();
        array_shift($params);
        foreach (self::$observers as $observer) {
            if(is_object($observer)){
                call_user_func_array(array((object)$observer,$event),$params);
            }
        }

    }

    public static function init($level){
        self::trigger_event('OnInit', $level);
    }

    public static function end(){
        self::trigger_event('OnEnd');
    }

    public static function error($msg,$label=null){
        self::trigger_event('OnError', $msg,$label);
    }

    public static function warning($msg,$label=null){
        self::trigger_event('OnWarning', $msg,$label);
    }

    public static function notice($msg,$label=null){
        self::trigger_event('OnNotice', $msg,$label);
    }

    public static function debug($msg,$label=null){
        self::trigger_event('OnDebug', $msg,$label);
    }

    public static function enter_group($label){
        self::trigger_event('OnGroupEnter', $label);
    }

    public static function leave_group(){
        self::trigger_event('OnGroupLeave');
    }
}