<?php

class BaseModel {

    public static $dbname = '';
    public static $table = '';
    public static $joined_tables = array();
    public static $primary_key = 'id';
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
        } elseif (method_exists($this, 'get_' . $property)) {
            $func = 'get_' . $property;
            return $this->$func();
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
        Debug::add('Finding ' . get_called_class() . ' with ' . var_export($options, true));
        if ($type == 'all') {
            $result = static::find_all($options);
            foreach ($result as $obj) {
                foreach ($additions as $key => $val) {
                    $obj->$key = $val;
                }
            }
        } elseif ($type == 'first') {
            $result = static::find_one($options);
            foreach ($additions as $key => $val) {
                $result->$key = $val;
            }
        } elseif ($type == 'last') {
            $options['order'] = static::$primary_key . ' DESC';
            $result = static::find_one($options);
            foreach ($additions as $key => $val) {
                $result->$key = $val;
            }
        } elseif (is_numeric($type)) {
            $result = static::find_by_pk(intval($type));
            foreach ($additions as $key => $val) {
                $result->$key = $val;
            }
        } else {
            throw new Exception('Find Error on ' . get_called_class());
        }
        Debug::dump($result);
        return $result;
    }

    private static function find_all($options) {
        if (!isset($options['limit'])) {
            $options['limit'] = static::$per_page;
        }
        if (!isset($options['offset']) && isset($options['conditions']) && isset($options['conditions']['page']) && $options['conditions']['page'] > 0) {
            $options['offset'] = ($options['conditions']['page'] - 1) * static::$per_page;
        }

        list($sql, $values) = static::get_find_query_and_values($options);
        return static::build_many_from_db($sql,$values);
    }

    private static function find_one($options) {
        $options['limit'] = 1;
        list($sql, $values) = static::get_find_query_and_values($options);
        return static::build_one_from_db($sql,$values);
    }

    private static function find_by_pk($id) {
        $pk = static::$primary_key;
        $options['conditions'] = array("{$pk}=:pk", 'pk' => $id);
        return static::find_one($options);
    }

    private static function get_find_query_and_values($options) {
        $select = new SqlQSelect(static::$table, static::$fields, static::$primary_key);
        if (isset($options['conditions']))
            $select->where($options['conditions']);
        if (isset($options['order']))
            $select->order($options['order']);
        if (isset($options['limit']))
            $select->limit($options['limit']);
        if (isset($options['offset']))
            $select->offset($options['offset']);
        return array((string) $select, $select->sql_values);
    }
    
    private static function build_one_from_db($sql, $values) {
        $cache = self::get_from_objstore($sql, $values);
        if ($cache) {
            $result = $cache;
        } else {
            $db = DatabaseManager::get_database(static::$dbname);
            $row = $db->query_and_fetch_one($sql, $values);
            $result = static::build($row, false, $db);
            self::put_to_objstore($sql, $values, $result);
        }
        return $result;
    }

    private static function build_many_from_db($sql, $values) {
        $cache = self::get_from_objstore($sql, $values);
        if ($cache) {
            $result = $cache;
        } else {
            $class_name = get_called_class();
            $db = DatabaseManager::get_database(static::$dbname);
            $results = $db->query_and_fetch($sql, function($row) use ($class_name, $db) {
                                return $class_name::build($row, false, $db);
                            }, $values);
            self::put_to_objstore($sql, $values, $results);
        }
        return $results;
    }
    
    private static function get_from_objstore($sql, $values){
        $key = ObjectStore::gen_key(array($sql,$values));
        return ObjectStore::get($key);
    }
    
    public static function put_to_objstore($sql, $values, $result){
        $key = ObjectStore::gen_key(array($sql,$values));
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