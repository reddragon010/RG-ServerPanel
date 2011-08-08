<?php

class i18n{
    private static $l = array();

    public static function load(){
        $lang = Environment::get_value('lang');
        foreach(glob(APP_ROOT . "/lang/{$lang}/*.ini") as $filename){
            $l = parse_ini_file($filename, true);
            self::$l = array_merge(self::$l, $l);
        }
    }
    
    public static function get(){
        $keys = func_get_args();
        $tmp = self::$l;
        foreach($keys as $key){
            if(is_array($tmp))
                $tmp = $tmp[$key];
        }
        return $tmp;
    }
}

?>
