<?php
namespace Dreamblaze\SqlS;

class Query_Insert extends Query_Base {
    private $values = array();
    private $sql_functions = array(
        '#NOW' => 'NOW()'
    );
    
    public function values($values) {
        $new_values = array();
        foreach($values as $key=>$val){
            $func_key = $this->placeholder_to_sql($val);
            if($func_key == false){
                $new_values[':' . $key] = $val;
            } else {
                $new_values[$func_key] = null;
            }
        }
        $this->values = $new_values;
        $this->fields = array_keys($values);
        return $this;
    }

    function head_part() {
        return 'INSERT INTO ' . $this->table;
    }
    
    function fields_part(){
        if (count($this->fields) <= 1 && $this->fields[0] == "*") {
            return '';
        } else {
            return '(' . self::fields_to_sql($this->fields, $this->table) . ')';
        }
        
    }
    
    function values_part(){
        return 'VALUES (' . implode(array_keys($this->values), ',') . ')';
    }
    
    function values_values(){
        return array_filter($this->values, function($item){
            return !is_null($item);
        });
    }
    
    protected function build() {
        $values = implode(',', $this->values);
        $tail = implode(' ', array($this->where));
        if (count($fields) <= 1 && $fields[0] == "*") {
            $sql = "INSERT INTO {$table} VALUES ($values) $tail";
        } else {
            $sql = "INSERT INTO {$table} ($fields) VALUES ($values) $tail";
        }
        return trim($sql);
    }

    private function placeholder_to_sql($placeholder){
        if(isset($this->sql_functions[$placeholder])){
            return $this->sql_functions[$placeholder];
        } else {
            return false;
        }
    }
}