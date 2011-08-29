<?php
//TODO:Still has unnamed placeholders!
class SqlS_QueryInsert extends SqlS_QueryBase {
    private $values = array();
    
    public function values($values) {
        $new_values = array();
        foreach($values as $key=>$val){
            $new_values[':' . $key] = $val;
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
        return $this->values;
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