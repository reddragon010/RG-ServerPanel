<?php

class Environment {

    static public $app_url;
    static public $app_theme_url;
    static private $database_connections;
    static private $config;

    private function __construct() {
        
    }

    public static function setup($name) {
        $config = parse_ini_file(__DIR__ . '/../config/' . $name . '_env.ini', true);
        self::$config = $config;
        self::set_app_url();
        self::$app_theme_url = self::$app_url . '/themes/' . $config['theme'];
    }

    public static function get_config_value($key) {
        if (isset(self::$config[$key])) {
            return self::$config[$key];
        } else {
            throw new Exception("Config-Key '$key' not found");
        }
    }

    public static function get_database($name) {
        if (isset(self::$database_connections[$name])) {
            return self::$database_connections[$name];
        } elseif (isset(self::$config['databases'][$name])) {
            self::connect_database($name, self::$config['databases'][$name]);
            return self::$database_connections[$name];
        } else {
            throw new Exception("Database not found!");
        }
    }

    private static function connect_database($name, $connection_string) {
        self::$database_connections[$name] = DatabaseConnection::instance($connection_string);
    }

    private static function set_app_url() {
        if (isset(self::$config['app_url_base'])) {
            self::$app_url = self::find_rooturl() . self::$config['app_url_base'];
        } else {
            self::$app_url = self::find_rooturl();
        }
    }

    private static function find_rooturl() {
        $pageURL = 'http';

        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
            $pageURL .= "s";

        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }

        return $pageURL;
    }

}
