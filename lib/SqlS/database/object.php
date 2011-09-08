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
    
    public static function set_dbname($dbname) {
        static::$dbname = $dbname;
    }
    
    public static function set_dbid($id){
        $databases = Config::instance('databases')->get_value(Environment::$name);
        $available_ids = array_keys($databases[static::$dbname]);
        if(in_array($id, $available_ids)){
            static::$dbid = $id;
            return true;
        } else {
            return false;
        }
    }
    
    public static function get_dbid(){
        if(!isset(static::$dbid)){
            return null;
        } else {
            return static::$dbid;
        }
    }
    
    public static function get_fields(){
        return static::$fields;
    }
    
    public function __construct($data=array(), $new=true){
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
        if(array_key_exists('v_' . $property, $this->data)) {
            return $this->data['v_' . $property];
        } elseif (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        } elseif (method_exists($this, 'get_' . $property)) {
            $func = 'get_' . $property;
            $var = $this->$func();
            $this->data['v_' . $property] = $var;
            return $var;
        } elseif($this->has_relation($property)) {
            return $this->resolve_relation($property);
        } else {
            return $this->$property;
        }
    }
    
    public function __isset($property) {
        if (array_key_exists($property, $this->data)) {
            return true;
        } elseif($this->has_relation($property)) {
            return true;
        } else {
            return isset($this->$property);
        }
    }
    
    public function __call($name, $arguments) {
        if(method_exists($this, 'get_'.$name)){
            $name = 'get_'.$name;
            return call_user_func(array($this,$name), $arguments);
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
    
    private function has_relation($relation){
        return isset(static::$relations[$relation]);
    }
    
    private function resolve_relation($model_id){
        $options = array();
        $relation = static::$relations[$model_id];
        if(isset($relation['conditions'])){
            foreach($relation['conditions'] as $rel_field=>$rel_cond){
                if(substr($rel_cond, 0, 1) == "#"){
                    $eval_result = null;
                    eval('$eval_result = ' . substr($rel_cond,1).';');
                    $rel_cond = $eval_result;
                } 
                $options['conditions'][$rel_field] = $rel_cond;
            }
        }
        $model = $relation['model'];
        
        if($relation['type'] == 'has_one'){
            $options['conditions'][$relation['field']] = $this->$relation['fk'];
            $result = $model::find('first', $options);
            $this->data[$model_id] = $result;
        } elseif($relation['type'] == 'has_many') {
            $options['conditions'][$relation['field']] = $this->$relation['fk'];
            $result = $model::find('all', $options);
            $this->data[$model::$plural] = $result;
        } else {
          throw new Exception('No Relation-Type given');  
        }
        return $result;
    }
}

