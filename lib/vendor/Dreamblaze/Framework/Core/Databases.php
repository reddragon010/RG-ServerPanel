<?php
namespace Dreamblaze\Framework\Core;
use \Dreamblaze\SqlS\Database_Manager;
use \Dreamblaze\SqlS\Database_Config;

class Databases {
    public static function setup(){
        $databases = Config::instance('databases')->to_array();
        $db_config_array = array();
        $db_config_array['loglevel'] = Config::instance('framework')->get_value('debug') ? 4 : 3;
        foreach($databases as $db_id=>$db_conn_string){
            if(is_array($db_conn_string)){
                $db_info = array();
                foreach((array)$db_conn_string as $key=>$val){
                    $db_info[$key] = self::parse_database_connection_url($val);
                }
            } else {
                $db_info = self::parse_database_connection_url($db_conn_string);
            }
            $db_config_array['dbs'][$db_id] = $db_info;
        }
        Database_Manager::init($db_config_array);
    }

    /**
     * Use this for any adapters that can take connection info in the form below
     * to set the adapters connection info.
     *
     * <code>
     * protocol://username:password@host[:port]/dbname
     * protocol://urlencoded%20username:urlencoded%20password@host[:port]/dbname?decode=true
     * protocol://username:password@unix(/some/file/path)/dbname
     * </code>
     *
     * Sqlite has a special syntax, as it does not need a database name or user authentication:
     *
     * <code>
     * sqlite://file.db
     * sqlite://../relative/path/to/file.db
     * sqlite://unix(/absolute/path/to/file.db)
     * sqlite://windows(c%2A/absolute/path/to/file.db)
     * </code>
     *
     * @param string $connection_url A connection URL
     * @return object the parsed URL as an object.
     */
    private static function parse_database_connection_url($connection_url) {
        $url = @parse_url($connection_url);

        if (!isset($url['host']))
            throw new \Exception('Database host must be specified in the connection string. If you want to specify an absolute filename, use e.g. sqlite://unix(/path/to/file)');

        $info = new Database_Config();
        $info->protocol = $url['scheme'];
        $info->host = $url['host'];
        $info->db = isset($url['path']) ? substr($url['path'], 1) : null;
        $info->user = isset($url['user']) ? $url['user'] : null;
        $info->pass = isset($url['pass']) ? $url['pass'] : null;

        $allow_blank_db = ($info->protocol == 'sqlite');

        if ($info->host == 'unix(') {
            $socket_database = $info->host . '/' . $info->db;

            if ($allow_blank_db)
                $unix_regex = '/^unix\((.+)\)\/?().*$/';
            else
                $unix_regex = '/^unix\((.+)\)\/(.+)$/';

            if (preg_match_all($unix_regex, $socket_database, $matches) > 0) {
                $info->host = $matches[1][0];
                $info->db = $matches[2][0];
            }
        } elseif (substr($info->host, 0, 8) == 'windows(') {
            $info->host = urldecode(substr($info->host, 8) . '/' . substr($info->db, 0, -1));
            $info->db = null;
        }

        if ($allow_blank_db && $info->db)
            $info->host .= '/' . $info->db;

        if (isset($url['port']))
            $info->port = $url['port'];

        if (strpos($connection_url, 'decode=true') !== false) {
            if ($info->user)
                $info->user = urldecode($info->user);

            if ($info->pass)
                $info->pass = urldecode($info->pass);
        }

        if (isset($url['query'])) {
            foreach (explode('/&/', $url['query']) as $pair) {
                list($name, $value) = explode('=', $pair);

                if ($name == 'charset')
                    $info->charset = $value;
            }
        }

        return $info;
    }
}

