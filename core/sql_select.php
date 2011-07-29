<?php

class SqlSelect extends SqlQuery {
    
    private $limit;
    private $offset;
    private $joinfields;
    private $join;
    private $order;
    private $count = false;
    private $distinct = false;
    
    public function distinct($distinct=true){
        $this->distinct = $distinct;
    }
    
    public function count($counting=true){
        $this->count = $counting;
    }
    
    public function limit($limit) {
        $this->limit = $limit;
    }
    
    public function offset($offset){
        $this->offset = $offset;
    }

    public function join($type, $ftable, $fields=array(''), $fk='id') {
        $this->joinfields += $fields;
        $this->join = array('table' => $ftable, 'key' => $fk, 'type' => $type);
    }

    public function order($fields) {
        if (!is_array($fields)) {
            $order = array($fields);
        } else {
            $order = $fields;
        }
        $this->order = array_filter($order);
    }

    protected function build() {
        $fields = implode(', ', $this->fields);
        $table = $this->table;
        $tail = implode(' ', array($this->table, $this->where, $this->join, $this->order, $this->limit));
        $sql = "SELECT $fields FROM $tail";
        return $sql;
    }

    function head_part(){
        $head = 'SELECT';
        if($this->distinct)
                $head .= ' DISTINCT';
        return $head;
    }
    
    function fields_part(){        
        $sqlfields = self::fields_to_sql($this->fields, $this->table);
        if($this->count){
            $sqlfields .= ', count(*) as c';
        }
        if(isset($this->join))
            $sqlfields .= self::fields_to_sql($this->joinfields, $this->join['table']);
        return $sqlfields;
    }
    
    function subhead_part(){
        return 'FROM ' . $this->table;
    }
    
    function order_part(){
        $order_part = '';
        if (!empty($this->order)) {
            $order_part = 'ORDER BY ';
            $a = array();
            
            foreach ($this->order as $order) {
                $pos = strpos($order, ' ');
                if ($pos === false) {
                    $field = $order;
                    $direction = '';
                } else {
                    $field = substr($order, 0, $pos);
                    $direction = substr($order, $pos);
                }
                if (in_array($field, $this->fields)) {
                    $a[] = "$field$direction";
                }
            }
            $order_part .= implode(',', $a);
        }
        return $order_part;
    }
    
    function limit_part(){
        $limit_part = '';
        if (isset($this->limit)) {
            $limit_part = "LIMIT {$this->limit}";
            if (isset($this->offset)) {
                $limit_part .= " OFFSET {$this->offset}";
            }
        }
        return $limit_part;
    }
    
    function join_part(){
        $join_part = '';
        if (isset($this->join)) {
            $join_part = "{$this->join['type']} JOIN {$this->join['table']} ON {$this->pk}={$this->join['key']}";
        }
        return $join_part;
    }
}