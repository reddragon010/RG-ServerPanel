<?php
class Model
{
	private $_data = array();
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
	
	public static function getFields($db=null){
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
	
	private static function find_all($options, $db){
		$table = static::$table;
		$fields = static::get_find_fields();
		if(isset($options['conditions'])){
			$where_str = ' WHERE ' . implode(' AND ', $options['conditions']);
		} else {
			$where_str = '';
		}
		if(!empty($sort)){
			$order_str = " ORDER BY {$options['order']}";
		} else {
			$order_str = '';
		}
		$sql = "SELECT {$fields} FROM {$table}{$where_str}{$order_str}";
        $class_name = get_called_class();
        $results = array();
        $db->query_and_fetch($sql, function($row) use ($class_name,$db,& $results){
        	$obj = $class_name::build($row,false,$db);
        	$results[] = $obj;
        });
        return $results;
	}
	
	private static function find_one($type, $options, $db){
		$table = static::$table;
		$fields = static::get_find_fields();
		if(isset($options['conditions'])){
			$where_str = ' WHERE ' . implode(' AND ', $options['conditions']);
		} else {
			$where_str = '';
		}
		
		if($type == 'last'){
			$order_str = 'ORDER BY ' . static::$primary_key . ' DESC';
		} else {
			$order_str = '';
		}
		
		$sql = "SELECT {$fields} FROM {$table}{$where_str}{$order_str} LIMIT 1";
        $row = $db->query_and_fetch_one($sql);
        return static::build($row,false,$db);
	}
	
	private static function find_by_pk($id, $db){
		$table = static::$table;
		$fields = static::get_find_fields();
		$pk = static::$primary_key;
		$sql = "SELECT {$fields} FROM {$table} WHERE {$pk}={$id} LIMIT 1";
        $row = $db->query_and_fetch_one($sql); 
        return static::build($row, false,$db);
	}
	
	public static function find($type, $options=array(), $db=null){
		global $config, $dbs;
		if(is_null($db)){
			$db = $dbs[static::$dbname];
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
	
	public static function build($data=array(),$new=true,$db=null){
		$class_name = get_called_class();
		$result = new $class_name($data,$new,$db);
		if(method_exists($result,'after_build')){
			$result->after_build();
		}
		return $result;
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
	
	/*
	public function reload(){
		$sql = "SELECT * FROM {$this->_table} WHERE id='{$this->id}' LIMIT 1";
		
		$this->_data = $row;
		
	}
	*/
	public function destroy(){
		$table = static::$table;
		$sql = "DELETE FROM {$table} WHERE id='{$this->id}'";
		$this->_db->query($sql);
		return true;
	}
	
	public function save(){
		if(method_exists($this,'before_save')){
			if(!$this->before_save())
				return false;
		}
		$table = static::$table;
		$data = array_intersect_key($this->_data, array_flip(static::getFields($table)));
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