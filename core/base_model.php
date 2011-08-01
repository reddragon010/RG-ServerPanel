<?php

class BaseModel {

    public static $dbname = '';
    public static $table = '';
    public static $joined_tables = array();
    public static $primary_key = 'id';
    public static $pk = 'id';
    public static $fields = array('*');
    public static $per_page = 25;
    public $data = array();
    public $errors = array();
    private $new;
    private $class_name;

    function __construct($properties=array(), $new=true) {
        $this->data = $properties;
        $this->new = $new;
        $this->class_name = get_called_class();
    }

    public function __set($property, $value) {
        return $this->data[$property] = $value;
    }

    public function __get($property) {
        if (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        } elseif(array_key_exists('v_' . $property, $this->data)) {
            return $this->data['v_' . $property];
        } elseif (method_exists($this, 'get_' . $property)) {
            $func = 'get_' . $property;
            $var = $this->$func();
            $this->data['v_' . $property] = $var;
            return $var;
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
    
    public function __call($name, $arguments) {
        if(method_exists($this, 'get_'.$name)){
            $name = 'get_'.$name;
            return call_user_func(array($this,$name), $arguments);
        } 
    }

    public static function set_dbname($dbname) {
        static::$dbname = $dbname;
    }

    public static function get_fields() {
        $db = DatabaseManager::get_database(static::$dbname);
        $fields = $db->columns_array(static::$table);
        array_diff($fields, array('created_at', 'updated_at'));
        return $fields;
    }

    public static function find($type, $options=array(), $additions=array()) {
        Debug::add('Finding ' . "($type)" . get_called_class() . ' with ' . var_export($options, true));
        $cache = self::get_from_objstore(array(get_called_class(), $type, $options, $additions));
        if ($cache) {
            $result = $cache;
        } else {
            $result = self::get_find_results($type, $options);
            if($result){
                foreach ($result as $result_key=>$result_val) {
                    foreach ($additions as $key => $val) {
                        $result[$result_key]->$key = $val;
                    }
                }
                if($type != 'all')
                    $result = $result[0];
                self::put_to_objstore(array(get_called_class(), $type, $options, $additions),$result);  
            }
        }
        Debug::stopTimer();
        return $result;
    }

    private static function get_find_results($type, $options) {
        $db = DatabaseManager::get_database(static::$dbname);
        $find = new SqlQFind(static::$table, static::$fields, static::$primary_key, static::$per_page);
        $find->find($type, $options);
        
        $sql = (string)$find;
        $values = $find->sql_values;

        $class_name = get_called_class();
        $results = $db->query_and_fetch($sql, function($row) use ($class_name, $db) {
                        return $class_name::build($row, false, $db);
                    }, $values);
        return $results;
    }
    
    private static function get_from_objstore($key_elements){
        $key = ObjectStore::gen_key($key_elements);
        return ObjectStore::get($key);
    }
    
    private static function put_to_objstore($key_elements, $result){
        $key = ObjectStore::gen_key($key_elements);
        ObjectStore::put($key,$result);
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
        //TODO: Maybe not SQL-Injection save
        $sql = new SqlQSelect(static::$table, static::$fields);
        if (isset($options['conditions'])) {
            $sql->where($options['conditions']);
        }
        $sql->count();
        $db = DatabaseManager::get_database(static::$dbname);
        $result = $db->query_and_fetch_one($sql);
        return $result["c"];
    }

    public static function create($params=array()) {
        $db = DatabaseManager::get_database(static::$dbname);
        $obj = static::build($params, true, $dbname);
        return $obj->save();
    }

    public function reload() {
        $obj = static::find($this->{static::$primary_key});
        if ($obj) {
            $this->data = $obj->_data;
            return true;
        } else {
            return false;
        }
    }

    public function destroy() {
        $table = static::$table;
        $sql = "DELETE FROM {$table} WHERE id='{$this->id}'";
        $db = DatabaseManager::get_database(static::$dbname);
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
        if (method_exists($this, 'before_save')) {
            if (!$this->before_save())
                return false;
        }

        if ($this->validate()) {
            $table = static::$table;
            $data = array_intersect_key($this->data, array_flip(static::$fields));
            $fields = array_keys($data);

            if ($this->new) {
                $sql = new SqlQInsert($table, $fields);
                $sql->values($data);
            } else {
                $sql = new SqlQUpdate($table, $fields);
                $sql->set($data);
                $sql->where(array('id' => $this->id));
            }

            $values = $sql->sql_values;
            $db = DatabaseManager::get_database(static::$dbname);
            $db->query($sql, $values);

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
            return false;
        }
    }

    public function validate() {
        return true;
    }

}