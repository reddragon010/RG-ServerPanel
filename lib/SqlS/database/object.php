<?php

class SqlS_DatabaseObject {
    public static $dbname;
    public static $table;
    public static $primary_key;
    public static $fields;
    public static $per_page;
    
    public $class_name;
    public $new;
    public $data = array();
    private $modified_data = array();
    
    public function __construct($data, $new=true){
        $this->data = $data;
        $this->new = $new;
        $this->class_name = get_called_class();
    }
    
    public function __set($property, $value) {
        if(in_array($property,static::$fields)){
            if((isset($this->data[$property]) && $this->data[$property] != $value) || !isset($this->data[$property]))
                $this->modified_data[] = $property;
            return $this->data[$property] = $value;
        } else {
            return $this->$property = $value;
        }
    }
    
    public function __get($property) {
        if (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        } else {
            return $this->$property;
        }
    }
    
    public function __isset($property) {
        if (array_key_exists($property, $this->data)) {
            return true;
        } else {
            return isset($this->$property);
        }
    }
    
    public static function build($data, $new=true){
        if (!empty($data)) {
            $class_name = get_called_class();
            $result = new $class_name($data, $new);
            return $result;
        } else {
            return false;
        }
    }
}

