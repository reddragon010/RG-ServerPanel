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
        if (!isset(self::$connections[$name]) || (!is_null($id) && !isset(self::$connections[$name][$id]))) {  
            self::connect_database($name, $id);
        }
        
        if(is_null($id)){
            if(isset(self::$connections[$name]))
                return self::$connections[$name];
        } else {
            if(isset(self::$connections[$name][$id]))
                return self::$connections[$name][$id];
        }
        throw new SqlS_DatabaseException("DB-Connection $name $id not found");
    }
    
    private static function connect_database($name, $id) {
        if(is_null($id) && isset(self::$configs[$name])){
            $info = self::$configs[$name];
        } elseif(isset(self::$configs[$name][$id]) && is_array(self::$configs[$name])) {
            $info = self::$configs[$name][$id];
        } else {
            throw new SqlS_DatabaseException("No DB with name $name available!");
        }
        
        Debug::queryRel("Connecting to DATABASE [<b>$name</b>] dns: " . var_export($info,true));
        
        if(get_class($info) != 'SqlS_DatabaseConfig'){
            throw new SqlS_DatabaseException("Invalid config on $name $id");
        }
        
        $fqclass = self::load_adapter_class($info->protocol);

        try {
            $connection = new $fqclass($info);
            $connection->protocol = $info->protocol;

            if (isset($info->charset))
                $connection->set_encoding($info->charset);
            
            $connection->set_timezone();
        } catch (PDOException $e) {
            throw new SqlS_DatabaseException(null,null,$e);
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
