<?php

class BaseModel extends SqlS_DatabaseObject implements ModelInterface {

    public static $primary_key = 'id';
    public static $per_page = 25;
    public static $relations = array();
    public $errors = array();

    public static function find($pk=null) {
        Debug::add('Try to find ' . get_called_class());
        $find = new QueryFind(get_called_class());
        return $find->find($pk);
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

    public static function create($params=array(), &$obj=null) {
        $obj = static::build($params, true);
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
        if(isset(static::$dbid)){
            $db = SqlS_DatabaseManager::get_database(static::$dbname,static::$dbid);
        } else {
            $db = SqlS_DatabaseManager::get_database(static::$dbname);
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

}