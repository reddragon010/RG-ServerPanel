<?php
class Model
{
	public $_data = array();
	private $_new;
	private $_db;
	private $_fields;
	
	public static $dbname = '';
	public static $table = '';
	public static $primary_key = 'id';
	public static $fields = null;
	
	public $errors = array();
	
	function __construct($properties=array(),$new=true,$db=null)
	{
		global $config, $dbs;
		$this->_data = $properties;
		$this->_new = $new;
		if(!is_null($db)){
			$this->_db = $db;
		} else {
			$this->_db = $dbs[static::$dbname];
		}
	}
	
	public function __set($property, $value){
	  return $this->_data[$property] = $value;
	}

	public function __get($property){
	  if(array_key_exists($property, $this->_data)){
			return $this->_data[$property];
		} else {
			return $this->$property;
		}
	}
	
	public function __isset($property){
		if(array_key_exists($property, $this->_data)){
			return true;
		} else {
			return isset($this->$property);
		}
	}
	
	public static function get_fields($db=null){
		global $config, $dbs;
		if(empty($db)){
			$db = $dbs[static::$dbname];
		}
		$fields = $db->columns_array(static::$table);
		array_diff($fields, array('created_at','updated_at'));
		return $fields;
	}
	
	private static function get_find_fields(){
		$fields = static::$fields;
		if(empty($fields)){
			return '*';
		} else {
			return implode(', ', $fields);
		}
	}
	
	private static function build_find_query($options){
		$where_part = '';
		$order_part = '';
		$limit_part = '';
        $join_part = '';
		$fields = static::get_find_fields();
		$table = static::$table;
		
		//build where
		if(isset($options['conditions'])){
			$where_part = " WHERE {$options['conditions'][0]}";
		}
		
		//build order
		if(isset($options['order'])){
			$order_part = ' ORDER BY ';
			$a = array_fill(0,count($options['order']),'?');
			$order_part .= implode(',', $a);
		}
		
		//build limit
		if(isset($options['limit'])){
			$limit_part = " LIMIT {$options['limit']}";
		}

        //build join
        if(isset($options['join'])){
            $join_part = " INNER JOIN {$options['join'][0]} ON {static::primary_key}={$options['join']['key']}";
        }
		
		$sql = "SELECT {$fields} FROM {$table}{$where_part}{$order_part}{$limit_part}{$join_part}";
		return $sql;
	}
	
	private static function build_find_values($options){
		$values = array();
        if(!empty($options['conditions'])){
            unset($options['conditions'][0]);
		    if(!empty($options['conditions'])){
			    $values += array_values($options['conditions']);
		    }
        }
        if(!empty($options['order'])){
		    if(is_array($options['order'])){
			    $values += array_values($options['order']);
			} else {
			    $values[] = $options['order'];
			}
		}
        return $values;
	}
	
	private static function find_all($options, $db){
		$sql = static::build_find_query($options);
		$values = static::build_find_values($options);
    $class_name = get_called_class();
    $results = array();

    $db->query_and_fetch($sql, function($row) use ($class_name,$db,& $results){
    	$obj = $class_name::build($row,false,$db);
    	$results[] = $obj;
    },$values);
    return $results;
	}
	
	private static function find_one($type, $options, $db){
		if($type == 'last'){
			$options['order'] = static::$primary_key . ' DESC';
		} 
		
		$sql = static::build_find_query($options);
		$values = static::build_find_values($options);
    $row = $db->query_and_fetch_one($sql,$values);
    return static::build($row,false,$db);
	}
	
	private static function find_by_pk($id, $db){
		$pk = static::$primary_key;
		$options['conditions'] = array("{$pk}=?", $id);
		$options['limit'] = 1;
		$sql = static::build_find_query($options);
		$values = static::build_find_values($options);
    $row = $db->query_and_fetch_one($sql,$values); 
    return static::build($row, false,$db);
	}
	
	public static function find($type, $options=array(), $db=null){
		global $config, $dbs;
		if(is_null($db)){
			$db = $dbs[static::$dbname];
		}
		if(method_exists('static','before_find')){
			static::before_find(&$options);
		}
		if($type == 'all'){
			return static::find_all($options, $db);
		} elseif($type == 'first' || $type == 'last'){
			return static::find_one($type, $options, $db);
		} elseif(is_numeric($type)){
			return static::find_by_pk(intval($type), $db);
		} else {
			throw new Exception('Find Error on ' . get_called_class());
		}
	}
	
	public static function build($data,$new=true,$db=null){
		if(!empty($data)){
			$class_name = get_called_class();
			$result = new $class_name($data,$new,$db);
			if(method_exists($result,'after_build')){
				$result->after_build();
			}
			return $result;
		} else {
			return false;
		}
	}
	
	public static function count($options=array(),$db=null){
		global $config, $dbs;
		if(empty($db)){
			$db = $dbs[static::$dbname];
		}
		$table = static::$table;
		$pk = static::$primary_key;
		if(isset($options['conditions'])){
			$where_str = implode(' AND ', $options['conditions']);
			$sql = "SELECT count($pk) FROM {$table} WHERE $where_str";
		} else {
			$sql = "SELECT count($pk) FROM {$table}";
		}
		$result = $db->query_and_fetch_one($sql);
		return $result["count($pk)"];
	}
	
	public static function create($params=array(), $db=null){
		global $dbs;
		if(empty($db)){
			$db = $dbs[static::$dbname];
		}
		$obj = static::build($params,true,$db);
		return $obj->save();
	}
	
	public function reload(){
		if($obj = static::find($this->{static::$primary_key})){
			$this->_data = $obj->_data;
			return true;
		} else {
			return false;
		}
	}
	
	public function destroy(){
		$table = static::$table;
		$sql = "DELETE FROM {$table} WHERE id='{$this->id}'";
		$this->_db->query($sql);
		return true;
	}
	
	public function update($data,$db=null){
		global $config, $dbs;
		if(is_null($db)){
			$db = $dbs[static::$dbname];
		}
		$table = static::$table;
		$fields = implode(',',array_keys($data));
		$sets = array();
		foreach($data as $key => $val){
			$sets[] = "$key=?";
		}
		$set_part = implode(',', $sets);
		$sql = "UPDATE {$table} SET {set_part} WHERE `id`=".$this->id;
		return $db->query($sql);
	}
	
	public function save(){
		if(method_exists($this,'before_save')){
			if(!$this->before_save())
				return false;
		}
		$table = static::$table;
		$data = array_intersect_key($this->_data, array_flip(static::get_fields()));
		$fields = array_keys($data);
		if($this->_new){
			$data_values = array();
			foreach($data as $val){
				$data_values[] = "'" . $val . "'";
			}
			$fields = implode(',',$fields);
			$values = implode(',', $data_values);
			$sql = "INSERT INTO {$table}({$fields}) VALUES ($values)";
		} else {
			$sql = "UPDATE {$table} SET ";
			$conj = '';
			foreach($fields as $field){
				if(isset($this->$field)){
					$sql .= $conj . $field . "='" . $this->$field . "'";
					$conj = ',';
				}
			}
			$sql .= " WHERE id='{$this->id}'";
		}
		if($this->validate()){
			$this->_db->query($sql);
			if(method_exists($this,'after_create') && $this->new){
				$this->after_create();
			} elseif(method_exists($this,'after_update')) {
				$this->after_update();
			}
			if(method_exists($this,'after_save')){
				$this->after_save();
			}
			return true;
		} else {
			return false;
		}
	}
	
	public function validate(){
		return true;
	}
}