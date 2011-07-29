<?php

abstract class SqlQBase {

    protected $fields;
    protected $pk;
    protected $table;
    protected $conds = array();
    
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
    
    public function __construct($table, $fields=array('*'), $pk='id') {
        $this->table = $table;
        $this->fields = $fields;
        $this->pk = $pk;
    }

    public function __toString() {
        return $this->build_sql();
    }
    
    public function __get($property){
        if($property == 'sql_values'){
            return $this->build_sql_values();
        } else {
            return $this->$property;
        }
    }
    
    private function build_sql(){
        $part_results = $this->collect_method_results($this->query_parts, '_part');
        $part_results = array_filter($part_results);
        $sql = implode(' ', $part_results);
        return $sql;
    }
    
    private function build_sql_values() {
        $part_values = $this->collect_method_results($this->query_parts, '_values');
        return $part_values;
    }
    
    abstract function head_part();
    
    function fields_part(){
        return self::fields_to_sql($this->fields, $this->table);
    }
    
    //--------------------------------------------
    //-- WHERE
    
    public function where($conds) {
        //TODO: Solve Empty-Param Problem
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
                    if(strpos($value,'%')){
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
        $values = array_flip($values);
        $values = array_map(function($item){
            return ':'.$item;
        }, $values);
        return array_flip($values);
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
                    $field = $table . '.' . $field;
                });
        return implode(', ', $fields);
    }

}