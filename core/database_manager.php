<?php

/**
 * 
 */
class DatabaseManager {

    static private $database_connections = array();

    public static function get_database($name) {
        if (!isset(self::$database_connections[$name])) {
            self::connect_database($name, Environment::get_config_value($name,'databases'));
        }
        return self::$database_connections[$name];
    }

    public static function disconnect_database($name) {
        if (isset(self::$database_connections[$name])) {
            unset(self::$database_connections[$name]);
        }
    }
    
    private static function connect_database($name, $connection_string) {
        Debug::queryRel("Connecting to DATABASE [<b>$name</b>] dns: $connection_string");
        self::$database_connections[$name] = DatabaseConnection::instance($connection_string);
    }
}
