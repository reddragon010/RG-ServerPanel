<?php

class SqlS_QuerySelect extends SqlS_QueryBase {
    protected $type = 'many'; /* none, one or many */
    private $limit;
    private $offset;
    private $joinfields = array();
    private $join;
    private $order;
    private $group_by;
    private $count = false;
    private $distinct = false;
    
    public function group_by($field){
        $this->group_by = $field;
        return $this;
    }
    
    public function distinct($distinct=true){
        $this->distinct = $distinct;
        return $this;
    }
    
    public function counting($counting=true){
        $this->count = $counting;
        return $this;
    }
    
    public function limit($limit) {
        if(is_numeric($limit)){
            $this->limit = $limit;
            if($limit == 1)
                $this->type = 'one';
        } else {
            $this->limit = null;
        }
        return $this;
    }
    
    public function offset($offset){
        $this->offset = (string)intval($offset);
        return $this;
    }

    public function join($type, $ftable, $fields=array(''), $fk='id') {
        $this->joinfields += $fields;
        $this->join = array('table' => $ftable, 'key' => $fk, 'type' => $type);
        $fields = array_map(function($field) use ($ftable) {return $ftable . "." . $field;}, $fields);
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }

    public function order($fields) {
        if (!is_array($fields)) {
            $order = array($fields);
        } else {
            $order = $fields;
        }
        $this->order = array_filter($order);
        return $this;
    }

    function head_part(){
        $head = 'SELECT';
        if($this->distinct)
                $head .= ' DISTINCT';
        return $head;
    }
    
    function fields_part(){
        if($this->count)
                return "count(*) as c";
        
        $sqlfields = self::fields_to_sql($this->fields, $this->table);
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
            if (isset($this->offset)) {
               $limit_part = "LIMIT {$this->offset},{$this->limit}";
            } else {
               $limit_part = "LIMIT {$this->limit}"; 
            }
        }
        return $limit_part;
    }
    
    function join_part(){
        $join_part = '';
        if (isset($this->join)) {
            $join_part = "{$this->join['type']} JOIN {$this->join['table']} ON {$this->table}.{$this->pk}={$this->join['table']}.{$this->join['key']}";
        }
        return $join_part;
    }
    
    function groupby_part(){
        $groupby_part = '';
        if (isset($this->group_by)) {
            $groupby_part = "GROUP BY {$this->group_by}";
        }
        return $groupby_part;
    }
}