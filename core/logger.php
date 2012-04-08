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

class Logger {
    const TYPE_DEBUG = 4;
    const TYPE_NOTICE = 3;
    const TYPE_WARNING = 2;
    const TYPE_ERROR = 1;

    private static $type_names = array(
        1 => 'ERROR',
        2 => 'WARN',
        3 => 'NOTE',
        4 => 'DBG'
    );

    private static $uid;
    private static $level = 4;
    private static $cache = array();
    private static $file_path = '../core.log';
    private static $request_start;
    private static $request_end;
    private static $query_count = 0;
    private static $query_time_min;
    private static $query_time_max;

    public static function init($level, $file_path){
        self::$uid = uniqid();
        self::$level = $level;
        self::$file_path = $file_path;
        self::$request_start = time();

        $start_msg = self::$uid . ' -> Request from ' . $_SERVER['REMOTE_ADDR'] . " to " . $_SERVER['REQUEST_URI'];
        self::push(self::TYPE_NOTICE,$start_msg);
    }

    public static function end(){
        self::$request_end = time();
        $exec_time = self::$request_end - self::$request_start;
        $msg = self::$uid . ' -> ' . sprintf('End Request (%s)',$exec_time . 'ms');
        self::push(self::TYPE_NOTICE, $msg);
    }

    public static function error($msg){
        self::push(self::TYPE_ERROR,$msg);
    }

    public static function warning($msg){
        self::push(self::TYPE_WARNING,$msg);
    }

    public static function notice($msg){
        self::push(self::TYPE_NOTICE,$msg);
    }

    public static function debug($msg){
        if(is_array($msg)){
            $msg = array_map(function($m){
                return Logger::stripNewline($m);
            }, $msg);
        } else {
            $msg = self::stripNewline($msg);
        }
        self::push(self::TYPE_DEBUG,$msg);
    }

    public static function query($query){
        self::debug('QUERY: ' . $query);
    }

    public static function stripNewline($text){
        $text = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $text);
        return trim(preg_replace("/\s+/", ' ',$text));
    }

    private static function push($type, $msg){
        if(self::$level >= $type){
            if(is_array($msg)) $msg = join("\n", $msg);
            $output = '[' . self::$type_names[$type] . '] [' . strftime("%y%m%d %T",time()) . '] ' . $msg . "\n";
            self::write($output);
        }
    }

    private static function write($text){
        $fh = fopen(self::$file_path,'a');
        fwrite($fh,$text);
        fclose($fh);
        self::$cache = '';
    }
}