<?php

/**
 * 
 */

namespace Dreamblaze\SqlS;
use Exception;
use PDOException;

class Database_Manager {

    static private $configs = array();
    static private $connections = array();

    public static function init(array $config){
        Logger::init($config['loglevel'],FRAMEWORK_ROOT . '/logs/sql.log');
        foreach($config['dbs'] as $key=>$value){
            self::add_database($key,$value);
        }
    }

    /*
     * @param string An unique identifier for this db
     * @param DatabaseConfig
     */
    public static function add_database($name, $info){
        if(is_array($info)){
            foreach($info as $key=>$val){
                self::check_config($val);
                self::$configs[$name][$key] = $val;
            }
        } else {
            self::check_config($info);
            self::$configs[$name] = $info;
        }
    }
    
    private static function check_config($config){
        if(get_class($config) != "Dreamblaze\\SqlS\\Database_Config"){
            throw new Exception('Wrong Database-Config format! Have to be an DatabaseConfig-Object');
        } else {
            return true;
        }
    }
    
    public static function get_database($name,$id) {
        if (!isset(self::$connections[$name]) || (!is_null($id) && !isset(self::$connections[$name][$id]))) {  
            self::connect_database($name, $id);
        }
        
        if(is_null($id)){
            if(is_object(self::$connections[$name]))
                return self::$connections[$name];
        } else {
            if(is_object(self::$connections[$name][$id]))
                return self::$connections[$name][$id];
        }
        throw new Database_Exception("DB-Connection $name $id not found");
    }
    
    private static function connect_database($name, $id) {
        if(is_null($id) && isset(self::$configs[$name])){
            $info = self::$configs[$name];
        } elseif(isset(self::$configs[$name][$id]) && is_array(self::$configs[$name])) {
            $info = self::$configs[$name][$id];
        } else {
            throw new Database_Exception("No DB with name $name $id available!");
        }
        
        Logger::debug("Connecting to DATABASE [<b>$name</b>] dns: " . var_export($info,true));
        
        if(get_class($info) != 'Dreamblaze\\SqlS\\Database_Config'){
            throw new Database_Exception("Invalid config on $name $id");
        }
        
        $fqclass = self::load_adapter_class($info->protocol);

        try {
            $connection = new $fqclass($info);
            $connection->protocol = $info->protocol;

            if (isset($info->charset))
                $connection->set_encoding($info->charset);
            
            $connection->set_timezone();
        } catch (PDOException $e) {
            throw new Database_Exception(null,null,$e);
        }
        
        if(is_null($id)){
            self::$connections[$name] = $connection;
        } else {
            self::$connections[$name][$id] = $connection;
        }
    }
    
    private static function load_adapter_class($adapter) {
        $class = 'Dreamblaze\\SqlS\\Adapter_' . ucwords($adapter);
        return $class;
    }
    
    public static function disconnect_database($name) {
        if (isset(self::$connections[$name])) {
            unset(self::$connections[$name]);
        }
    }
}
