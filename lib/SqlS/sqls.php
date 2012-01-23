<?php

class SqlS {
    public static function init(array $config){
        SqlS_ToolLogger::init($config['loglevel'],FRAMEWORK_ROOT . '/logs/sql.log');
        foreach($config['dbs'] as $key=>$value){
            SqlS_DatabaseManager::add_database($key,$value);
        }
    }
}