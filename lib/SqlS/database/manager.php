<?php

/**
 * 
 */
class SqlS_DatabaseManager {

    static private $configs = array();
    static private $connections = array();
    
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
        if(get_class($config) != "SqlS_DatabaseConfig"){
            throw new Exception('Wrong Database-Config format! Have to be an DatabaseConfig-Object');
        } else {
            return true;
        }
    }
    
    public static function get_database($name,$id) {
        //TODO: Find A Clean Solution For "global-only set_dbid"-Bug
        if (!isset(self::$connections[$name]) || (!is_null($id) && is_array(self::$connections[$name]) && !isset(self::$connections[$name][$id]))) {  
            self::connect_database($name, $id);
        }
        
        if(isset(self::$connections[$name]) && !is_array(self::$connections[$name])){
            return self::$connections[$name];
        } elseif(is_array(self::$connections[$name]) && isset(self::$connections[$name][$id])) {
            return self::$connections[$name][$id];
        }
    }
    
    private static function connect_database($name, $id) {
        if(is_null($id) && isset(self::$configs[$name])){
            $info = self::$configs[$name];
        } elseif(isset(self::$configs[$name][$id])) {
            $info = self::$configs[$name][$id];
        } else {
            throw new Exception("No DB with name $name available!");
        }
        
        Debug::queryRel("Connecting to DATABASE [<b>$name</b>] dns: " . var_export($info,true));
        
        $fqclass = self::load_adapter_class($info->protocol);

        try {
            $connection = new $fqclass($info);
            $connection->protocol = $info->protocol;

            if (isset($info->charset))
                $connection->set_encoding($info->charset);
            
            $connection->set_timezone();
        } catch (PDOException $e) {
            throw new SqlS_DatabaseException($e);
        }
        
        if(is_null($id)){
            self::$connections[$name] = $connection;
        } else {
            self::$connections[$name][$id] = $connection;
        }
    }
    
    private static function load_adapter_class($adapter) {
        $class = 'SqlS_Adapter' . ucwords($adapter);
        return $class;
    }
    
    public static function disconnect_database($name) {
        if (isset(self::$connections[$name])) {
            unset(self::$connections[$name]);
        }
    }
}
