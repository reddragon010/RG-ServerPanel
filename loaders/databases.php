<?php
$env = Environment::$name;
$database_config = Config::instance('databases');
$databases = $database_config->get_value($env);
$db_config_array = array();
$db_config_array['loglevel'] = Environment::get_value('debug') ? 4 : 3;
foreach($databases as $db_id=>$db_conn_string){
    if(is_array($db_conn_string)){
        $db_info = array();
        foreach($db_conn_string as $key=>$val){
            $db_info[$key] = Toolbox::parse_database_connection_url($val);
        }
    } else {
        $db_info = Toolbox::parse_database_connection_url($db_conn_string);
    }
    $db_config_array['dbs'][$db_id] = $db_info;
}
SqlS::init($db_config_array);