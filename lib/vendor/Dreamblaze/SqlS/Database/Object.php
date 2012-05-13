<?php

namespace Dreamblaze\SqlS;

class Database_Object {
    public static $dbname;
    public static $table;
    public static $primary_key;
    public static $fields;
    public static $per_page;

    public static $dbid = null;
    public static $multidb = false;

    public $class_name;
    public $new;
    public $data = array();
    public $modified_data = array();
    
    public static function set_dbname($dbname) {
        static::$dbname = $dbname;
    }

    //TODO: In Love With The Framework (Strong Coupling)!!
    public static function set_dbid($id){
        $databases = Config::instance('databases')->get_value(Environment::$name);
        $available_ids = array_keys($databases[static::$dbname]);
        if(in_array($id, $available_ids)){
            static::$dbid = $id;
            return true;
        } else {
            throw new Database_Exception("Can't find DB-ID " . $id . " for " . static::$dbname);
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
            if (method_exists($result, 'after_build')) {
                $result->after_build();
            }
            return $result;
        } else {
            return false;
        }
    }
    
    private function has_relation($relation){
        return isset(static::$relations[$relation]);
    }
    
    private function resolve_relation($model_id){
        $relation = static::$relations[$model_id];
        $model = $relation['model'];
        
        if(isset($relation['type'])){
            $find = $model::find()->where(array($relation['field'] => $this->$relation['fk']));
        } else {
            throw new Exception('No Relation-Type given'); 
        }
        
        if(isset($relation['conditions'])){
            foreach($relation['conditions'] as $rel_field=>$rel_cond){
                if(substr($rel_cond, 0, 1) == "#"){
                    $eval_result = null;
                    eval('$eval_result = ' . substr($rel_cond,1).';');
                    $rel_cond = $eval_result;
                } 
                $find->where(array($rel_field => $rel_cond));
            }
        }
        
        if(isset($relation['lambda'])){
            foreach($relation['lambda'] as $code){
                $func = create_function('&$find,$lambda', $code);
                $func($find,$this);
            }
        }
        
        if(isset($relation['scopes'])){
            foreach($relation['scopes'] as $scope=>$args){
                call_user_func_array(array($find, $scope), $args);
            }
        }

        if($relation['type'] == 'has_one'){
            $rel_name = $model_id;
            $rel_data = $find->first();
        } elseif($relation['type'] == 'has_many') {
            if(!isset($model::$plural)) throw new Exception('No Plural for model ' . $model . ' available!');
            $rel_name = $model::$plural;
            $rel_data = $find->all();
        } else {
           throw new Exception('Unknown Relation-Type given'); 
        }

        $this->data[$rel_name] = $rel_data;
        return $this->data[$rel_name];
    }
}

