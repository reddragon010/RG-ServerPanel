<?php

class SqlS_QueryUpdate extends SqlS_QueryBase {

    private $values = array();
    private $lowercase_fields;

    public function __construct($table, $fields, $pk = 'id') {
        parent::__construct($table, $fields, $pk);
    }
    
    public function set($values) {
        $fields = unserialize(strtolower(serialize($this->fields)));
        $this->lowercase_fields = $fields;
        $value_keys = array_filter(array_keys($values), function($item) use ($fields){
            return in_array(strtolower($item), $fields);
        });
        $this->values = array_intersect_key($values, array_flip($value_keys));
        return $this;
    }
    
    function head_part() {
        return "UPDATE {$this->table}";
    }
    
    function fields_part() {
        return '';
    }
    
    function values_part(){
        $fields = $this->fields;
        $values = $this->values;
        $sets = array();
        foreach($values as $value=>$content){
            $field_id = array_search(strtolower($value), $this->lowercase_fields);
            $sets[] = $fields[$field_id] . ' = :' . $value. ' ';
        }
        $sets_string = implode(',', $sets);
        return 'SET ' . $sets_string . ' ';
    }
    
    function values_values(){
        $values = $this->values;
        $result = array();
        foreach($values as $key=>$val){
            $result[':'.$key] = $val;
        }
        return $result;
    }
}