<?php

class BaseModel extends SqlS_DatabaseObject implements ModelInterface {

    public static $primary_key = 'id';
    public static $per_page = 25;
    public static $relations = array();
    public $errors = array();

    public function __get($property) {
        if(array_key_exists('v_' . $property, $this->data)) {
            return $this->data['v_' . $property];
        } elseif (method_exists($this, 'get_' . $property)) {
            $func = 'get_' . $property;
            $var = $this->$func();
            $this->data['v_' . $property] = $var;
            return $var;
        } elseif($this->has_relation($property)) {
            return $this->resolve_relation($property);
        } else {
            return parent::__get($property);
        }
    }
    
    public function __call($name, $arguments) {
        if(method_exists($this, 'get_'.$name)){
            $name = 'get_'.$name;
            return call_user_func(array($this,$name), $arguments);
        }
    }

    public static function set_dbname($dbname) {
        static::$dbname = $dbname;
    }
    
    public static function get_fields(){
        return static::$fields;
    }

    public static function find($type, $options=array(), $additions=array()) {
        Debug::add('Finding ' . "($type)" . get_called_class() . ' with ' . var_export($options, true));
        $find = new QueryFind(get_called_class());
        $find->find($type,$options);
        return $find->execute($additions);
    }

    public static function build($data, $new=true) {
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

    public static function count($options=array()) {
        $sql = SqlS_QueryBuilder::count(get_called_class());
        if (isset($options['conditions'])) {
            $sql->where($options['conditions']);
        }
        $result = $sql->execute();
        Debug::dump($result);
        return $result->c;
    }

    public static function create($params=array(), &$obj=null) {
        $db = SqlS_DatabaseManager::get_database(static::$dbname);
        $obj = static::build($params, true, $dbname);
        return $obj->save();
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
        $db = SqlS_DatabaseManager::get_database(static::$dbname);
        $db->query($sql);
        return true;
    }

    public function update($params, $db=null) {
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
        
        if (method_exists($this, 'before_save')) {
            if (!$this->before_save()){
                $this->errors[] = "before_save failed";
                return false;
            }
        }

        if ($this->validate()) {
            $data = array_intersect_key($this->data, array_flip(static::$fields));
            if ($this->new) {
                $sql = SqlS_QueryBuilder::insert(get_called_class());
                $sql->values($data);
            } else {
                $pk = static::$primary_key;
                $data = array_intersect_key($data, array_flip($this->modified_data));
                $sql = SqlS_QueryBuilder::update(get_called_class());
                $sql->set($data);
                $sql->where(array($pk => $this->$pk));
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
    
    private function resolve_relation($model){
        $model_id = strtolower($model);
        if($this->has_relation($model_id)){
            if(method_exists($this,'before_relation'))
                    $this->before_relation($model);
            
            $relation = static::$relations[$model_id];
            if($relation['type'] == 'has_one'){
                $options['conditions'][$relation['field']] = $this->$relation['fk'];
                $this->data[$model_id] = $model::find('first', $options);
            } elseif($relation['type'] == 'has_many') {
                $options['conditions'][$relation['field']] = $this->$relation['fk'];
                $this->data[$model::$plural] = $model::find('all', $options);
            } else {
              throw new Exception('No Relation-Type given');  
            }
        } else {
            throw new Exception("Model not related to $model");
        }
    }

}