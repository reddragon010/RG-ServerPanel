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
    public static $relations = array();

    public $errors = array();
    public $class_name;
    public $new;
    public $data = array();
    public $modified_data = array();
    
    public static function set_dbname($dbname) {
        static::$dbname = $dbname;
    }

    public static function set_dbid($id){
        static::$dbid = $id;
        return true;
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

    public static function find($pk=null) {
        $find = new Query_Find(get_called_class());
        return $find->find($pk);
    }

    public static function create($params=array(), &$obj=null) {
        $obj = static::build($params, true);
        return $obj->save();
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
            return call_user_func_array(array($this,$name), $arguments);
        }
    }

    public function reload() {
        $obj = static::find($this->{static::$primary_key});
        if ($obj) {
            $this->data = $obj->data;
            return true;
        } else {
            return false;
        }
    }

    public function destroy() {
        $table = static::$table;
        $pk = static::$primary_key;
        $sql = "DELETE FROM {$table} WHERE {$pk}='{$this->$pk}'";
        if(isset(static::$dbid)){
            $db = Database_Manager::get_database(static::$dbname,static::$dbid);
        } else {
            $db = Database_Manager::get_database(static::$dbname,null);
        }
        $db->query($sql);
        return true;
    }

    public function update($params) {
        $params = array_filter($params);
        foreach ($params as $param => $val) {
            if ((isset($this->$param) && $this->$param != $val)) {
                $this->$param = $val;
            }
        }
        return $this->save();
    }

    public function save() {
        if(empty($this->modified_data) && !$this->new)
            return true;


        if ($this->validate()) {
            $data = array_intersect_key($this->data, array_flip(static::$fields));
            if ($this->new) {
                $sql = Query_Builder::insert(get_called_class());
                $sql->values($data);
            } else {
                $pk = static::$primary_key;
                $data = array_intersect_key($data, array_flip($this->modified_data));
                $sql = Query_Builder::update(get_called_class());
                $sql->set($data);
                $sql->where(array($pk => $this->$pk));
            }

            if (method_exists($this, 'before_save')) {
                if (!$this->before_save($sql)){
                    $this->errors[] = "before_save failed";
                    return false;
                }
            }

            if(!$sql->execute()){
                $this->error[] = "Save failed on SQL-Level!";
                return false;
            }

            if (method_exists($this, 'after_create') && $this->new) {
                $this->after_create();
            } elseif (method_exists($this, 'after_update')) {
                $this->after_update();
            }
            if (method_exists($this, 'after_save')) {
                $this->after_save();
            }
            return true;
        } else {
            $this->errors[] = "validation failed";
            return false;
        }
    }

    public function validate() {
        return true;
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

