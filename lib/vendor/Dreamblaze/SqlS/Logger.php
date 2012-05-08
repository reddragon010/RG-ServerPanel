<?php

namespace Dreamblaze\SqlS;

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

    private static $level = 4;
    private static $cache = array();
    private static $file_path = '../sqls.log';

    public static function init($level, $file_path){
        self::$level = $level;
        self::$file_path = $file_path;
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