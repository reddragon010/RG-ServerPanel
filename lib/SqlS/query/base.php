<?php

abstract class SqlS_QueryBase {
    
    protected $result_name;
    protected $type = 'none'; /* none, one or many */
    protected $fields;
    protected $pk;
    protected $table;
    protected $dbname;
    protected $conds = array();
    
    protected $sql;
    protected $sql_values;


    protected $query_parts = array(
        'head',
        'fields',
        'subhead',
        'join',
        'values',
        'where', 
        'order', 
        'limit',
        'offset'
    );
    
    public function __construct($dbobject) {
        $this->table = $dbobject::$table;
        $this->dbname = $dbobject::$dbname;
        $this->fields = $dbobject::$fields;
        $this->pk = $dbobject::$primary_key;
        
        if(!is_string($dbobject))
            $dbobject = get_class();
        
        $this->result_name = $dbobject;      
    }
    
    public function execute(){
        $sql = $this->build_sql();
        $values = $this->build_sql_values();
        $db = SqlS_DatabaseManager::get_database($this->dbname);
        $class_name = $this->result_name;
        switch($this->type){
            case 'none':
                $result = $db->query($sql,$values);
                break;
            case 'one':
                $result = $class_name::build($db->query_and_fetch_one($sql,$values));
                break;
            case 'many':
                $result = $db->query_and_fetch($sql, function($row) use ($class_name, $db) {
                    return $class_name::build($row, false, $db);
                }, $values);
                break; 
        }
        return $result;   
    }
    
    protected function build_sql(){
        if(empty($this->sql)){
            $part_results = $this->collect_method_results($this->query_parts, '_part');
            $part_results = array_filter($part_results);
            $sql = implode(' ', $part_results);
            $this->sql = trim($sql);
        }
        return $this->sql;
    }
    
    protected function build_sql_values() {
        if(empty($this->sql_values)){
            $this->sql_values = $this->collect_method_results($this->query_parts, '_values');
        }
        return $this->sql_values;
    }
    
    abstract function head_part();
    
    function fields_part(){
        return self::fields_to_sql($this->fields, $this->table);
    }
    
    //--------------------------------------------
    //-- WHERE
    
    public function where($conds) {
        //TODO: Solve Empty-Param Problem
        $conds = array_filter($conds,function($elem){
            return $elem != ''; 
        });
        if (!isset($conds[0])) {
            $flipped_fields = array_flip($this->fields);
            $fields = array_intersect_key($conds, $flipped_fields);
            if (!empty($fields)) {
                $merged_params = array();
                foreach ($fields as $field => $value) {
                    if(strpos($field,'.') === false){
                        $ufield = $this->table . '.' . $field;
                    } else {
                        $ufield = $field;
                    }
                    if(strpos($value,'%') !== false){
                        $marged_params[] = "$ufield LIKE :$field";
                    } else {
                        $marged_params[] = "$ufield = :$field";
                    }
                }
                $sql_conds = array(join(' AND ', $marged_params));
                $vals = $fields;
                $conds = array_merge($sql_conds, $vals);
            } else {
                $conds = null;
            }
        }
        $this->conds = $conds;
        return $this;
    }
    
    function where_values(){
        $table = $this->table;
        $conds = $this->conds;
        $values = array();
        if (!empty($conds)) {
            unset($conds[0]);
            if (!empty($conds)) {
                $values = $conds;
            }
        }
        $values = array_filter($values);
        $result = array();
        foreach($values as $key=>$val){
            $result[':'.$key] = $val;
        }
        return $result;
    }

    function where_part(){
        if (!empty($this->conds)) {
            return "WHERE {$this->conds[0]}";
        }
    }
    
    //--
    //--------------------------------------------
    
    private function collect_method_results($method_names, $suffix){
        $part_results = array();
        foreach($method_names as $part){
            $method_name = $part . $suffix;
            if(method_exists($this, $method_name)){
                $result = $this->$method_name();
                if(is_array($result)){
                    $part_results = array_merge($part_results, $result);
                } else {
                    $part_results[] = $result;
                }
            }
        }
        return $part_results;
    }
    
    static function fields_to_sql($fields, $table) {
        array_walk($fields, function(&$field) use($table) {
                    if(strpos($field, '(') === false)
                        $field = $table . '.' . $field;
                });
        return implode(', ', $fields);
    }

}