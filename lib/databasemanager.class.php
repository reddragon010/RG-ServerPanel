<?php

/**
 * 
 */
class DatabaseManager {

    static private $databases = array();

    public static function get_database($name) {
        if (isset(self::$databases[$name])) {
            return self::$databases[$name];
        } else {
            self::connect_database(Environment::database_config($name));
        }
    }

    public static function connect_database($name, $connection_string) {
        self::$databases[$name] = DatabaseConnection::instance($connection_string);
    }

    public static function disconnect_database($name) {
        if (isset(self::$databases[$name])) {
            unset(self::$databases[$name]);
        }
    }

}
