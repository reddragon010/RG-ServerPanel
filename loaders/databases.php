<?php
$env = Environment::$name;
$database_config = Config::instance('databases');
$databases = $database_config->get_value($env);
foreach($databases as $db_id=>$db_conn_string){
    if(is_array($db_conn_string)){
        $db_info = array();
        foreach($db_conn_string as $key=>$val){
            $db_info[$key] = Toolbox::parse_database_connection_url($val);
        }
    } else {
        $db_info = Toolbox::parse_database_connection_url($db_conn_string);
    }
    SqlS_DatabaseManager::add_database($db_id, $db_info);
}
