<?php

abstract class SQLQuery {

    protected $fields;
    protected $pk;
    protected $table = "";
    protected $where = "";

    public function __construct($table, $fields=array('*'), $pk='id') {
        $this->table = $table;
        $this->fields = $fields;
        $this->pk = $pk;
    }

    public function __toString() {
        return $this->build();
    }

    abstract protected function build();

    public function where($conds) {
        $this->where = "WHERE $conds";
    }

}