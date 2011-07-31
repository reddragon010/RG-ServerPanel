<?php
class Environment {
    static private $config;
    
    static private $config_file_path = '';
    
    private function __construct() {}

    public static function setup($name) {
        self::$config_file_path = __DIR__ . '/../config/' . $name . '_env.ini';
        self::load_config();
        self::set_timezone();
        self::load_debug();
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
    
    private static function load_config(){
        if(file_exists(self::$config_file_path)){
            self::$config = parse_ini_file(self::$config_file_path, true);
        } else {
            throw new Exception("Config-File doesn't exist");
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
    
    private static function load_debug(){
        if(self::$config['debug']){
            Debug::setup();
        }
    }
    
    public static function trigger_error($msg, $level){
        trigger_error($msg, $level);
    }

}
