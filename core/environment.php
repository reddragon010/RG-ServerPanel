<?php
class Environment {
    public static $name;
    
    private function __construct() {}
    
    public static function setup($name) {
        self::$name = $name;
        Config::exists('envs');
        Config::instance('envs')->get_value($name);
        self::set_timezone();
    }
    
    public static function get_value($key) {
        $config = Config::instance('envs');
        return $config->get_value(self::$name, $key);
    }
    
    private static function set_timezone(){
        try{
            $timezone = self::get_value('timezone');
        } catch(Exception $e) {
            $timezone = 'Europe/Vienna';
        }
        date_default_timezone_set($timezone);   
    }
    
    public static function trigger_error($msg, $level){
        trigger_error($msg, $level);
    }

}
