<?php
//TODO: Needs some work - probably broken
class SqlUpdate extends SQLQuery {

    private $values = array();

    public function set($values) {
        $this->values = $values;
    }
    
    function head_part() {
        return 'UPDATE {$this->table}';
    }
    
    function values_part(){
        $set_vals = array();
        foreach ($this->values as $key => $val) {
            if (in_array($key, $this->fields))
                $set_vals[] = "$key='$val'";
        }
        $sets = implode(',', $set_vals);
        return 'SET (' . $sets . ')';
    }
}