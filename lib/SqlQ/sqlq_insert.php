<?php
//TODO:Still has unnamed placeholders!
class SqlQInsert extends SqlQBase {
    private $values = array();
    
    public function values($values) {
        $this->values = $values;
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
        return 'VALUES (' . implode(array_fill(0, count($this->values), '?'),',') . ')';
    }
    
    function values_values(){
        return array_values($this->values);
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

}