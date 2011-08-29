<?php

/**
 * Adapter for MySQL.
 *
 * @package ActiveRecord
 */
class SqlS_AdapterMysql extends SqlS_DatabaseConnection {

    static $DEFAULT_PORT = 3306;

    public function limit($sql, $offset, $limit) {
        $offset = is_null($offset) ? '' : intval($offset) . ',';
        $limit = intval($limit);
        return "$sql LIMIT {$offset}$limit";
    }

    public function query_column_info($table) {
        return $this->query("SHOW COLUMNS FROM $table");
    }

    public function query_for_tables() {
        return $this->query('SHOW TABLES');
    }

    public function set_encoding($charset) {
        $params = array($charset);
        $this->query('SET NAMES ?', $params);
    }
    
    public function set_timezone(){
        $now = new DateTime();
        $mins = $now->getOffset() / 60;
        $sign = ($mins < 0 ? -1 : 1);
        $mins = abs($mins);
        $hrs = floor($mins / 60);
        $mins -= $hrs * 60;
        $offset = sprintf('%+d:%02d', $hrs*$sign, $mins);
        $params = array($offset);
        $this->query('SET time_zone=?', $params);
    }

    public function accepts_limit_and_order_for_update_and_delete() {
        return true;
    }

    public function native_database_types() {
        return array(
            'primary_key' => 'int(11) DEFAULT NULL auto_increment PRIMARY KEY',
            'string' => array('name' => 'varchar', 'length' => 255),
            'text' => array('name' => 'text'),
            'integer' => array('name' => 'int', 'length' => 11),
            'float' => array('name' => 'float'),
            'datetime' => array('name' => 'datetime'),
            'timestamp' => array('name' => 'datetime'),
            'time' => array('name' => 'time'),
            'date' => array('name' => 'date'),
            'binary' => array('name' => 'blob'),
            'boolean' => array('name' => 'tinyint', 'length' => 1)
        );
    }

}

?>
