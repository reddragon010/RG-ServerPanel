<?php
class Environment {
    static private $config;

    private function __construct() {}

    public static function setup($name) {
        self::$config = parse_ini_file(__DIR__ . '/../config/' . $name . '_env.ini', true);
        self::set_timezone();
        if(self::$config['debug']){
            Debug::setup();
        }
    }

    public static function get_config_value($key, $scope='') {
        if(!empty($scope) && isset(self::$config[$scope][$key])){
            return self::$config[$scope][$key];
        } elseif (isset(self::$config[$key])) {
            return self::$config[$key];
        } else {
            throw new Exception("Config-Key '$key' not found");
        }
    }
    
    private static function set_timezone(){
        try{
            $timezone = self::get_config_value('timezone');
        } catch(Exception $e) {
            $timezone = 'Europe/Vienna';
        }
        date_default_timezone_set($timezone);   
    }
    
    public static function trigger_error($msg, $level){
        trigger_error($msg, $level);
    }

}
