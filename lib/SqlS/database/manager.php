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
    public static function add_database($id, $info){
        if(get_class($info) != "SqlS_DatabaseConfig")
            throw new Exception('Wrong Database-Config format! Have to be an DatabaseConfig-Object');
        
        self::$configs[$id] = $info;
    }
    
    public static function get_database($name) {
        if (!isset(self::$connections[$name])) {
            $info = self::$configs[$name];
            self::connect_database($name, $info);
        }
        return self::$connections[$name];
    }

    public static function disconnect_database($name) {
        if (isset(self::$connections[$name])) {
            unset(self::$connections[$name]);
        }
    }
    
    private static function connect_database($name, $info) {
        if (!$info)
            throw new Exception("Empty connection string");
        
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
        
        self::$connections[$name] = $connection;
    }
    
    private static function load_adapter_class($adapter) {
        $class = 'SqlS_Adapter' . ucwords($adapter);
        return $class;
    }
    
    
}
