<?php

class SQLSelect extends SQLQuery {

    private $limit = "";

    public function limit($limit, $offset=null) {
        if (is_null($offset)) {
            $this->limit = "LIMIT $limit";
        } else {
            $this->limit = "LIMIT $limit OFFSET $offset";
        }
    }

    public function join($type, $ftable, $fields=array(''), $fk='id') {
        $ptable = $this->table;
        $pk = $this->pk;
        $this->fields += $fields;
        $this->join = "$type JOIN $table ON $ptable.$pk=$ftable.$fk";
    }

    public function sort($fields) {
        if (is_array($fields)) {
            $this->order = 'ORDER BY ' . implode(',', $fields);
        } else {
            $this->order = 'ORDER BY ' . $fields;
        }
    }

    protected function build() {
        $fields = implode(', ', $this->fields);
        $table = $this->table;
        $tail = implode(' ', array($this->table, $this->where, $this->join, $this->order, $this->limit));
        $sql = "SELECT $fields FROM $tail";
        return $sql;
    }

}