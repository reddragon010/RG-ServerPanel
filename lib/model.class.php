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
	
	function __construct($properties=array(),$new=true,$db=null)
	{
		global $config, $dbs;
		$this->_data = $properties;
		$this->_new = $new;
		if(empty($db)){
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
	
	private static function get_find_all_query($options){
		$table = static::$table;
		if(!empty($conditions)){
			$where_str = ' WHERE ' . implode(' AND ', $conditions);
		} else {
			$where_str = '';
		}
		if(!empty($sort)){
			$order_str = " ORDER BY {$options['order']}";
		} else {
			$order_str = '';
		}
		return "SELECT * FROM {$table}{$where_str}{$order_str}";
	}
	
	private static function get_find_one_query($type, $options){
		$table = static::$table;
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
		
		return "SELECT * FROM {$table}{$where_str}{$order_str} LIMIT 1";
	}
	
	private static function get_find_pk_query($id){
		$table = static::$table;
		$pk = static::$primary_key;
		return "SELECT * FROM {$table} WHERE {$pk}={$id} LIMIT 1";
	}
	
	public static function find($type, $options=array(), $db=null){
		global $config, $dbs;
		if(empty($db)){
			$db = $dbs[static::$dbname];
		}
		
		$table = static::$table;
		
		if($type == 'all'){
			$sql = static::get_find_all_query($options);
			$class_name = get_called_class();
			$db->query_and_fetch($sql, function($row) use ($class_name,$db){
				$obj = new $class_name($row,false,$db);
				if(method_exists($obj,'after_find')){
					$obj->after_find();
				}
				return $obj;
			});
		} elseif($type == 'first' || $type == 'last'){
			$sql = static::get_find_one_query($type, $options);
			$row = $db->query_and_fetch_one($sql);
			return static::build($row,false,$db);
		} elseif(is_int($type)){
			$sql = static::get_find_pk_query($type);
			$row = $db->query_and_fetch_one($sql); 
			return static::build($row, false,$db);
		}
		
	}
	
	public static function build($data=array(),$new=true,$db=null){
		$class_name = get_called_class();
		$result = new $class_name($data,$new,$db);
		return $result;
	}
	
	public static function count($options=array(),$db=null){
		global $config, $dbs;
		if(empty($db)){
			$db = $dbs[static::$dbname];
		}
		$table = static::$table;
		$pk = static::$primary_key;
		if(!empty($conditions)){
			$where_str = implode(' AND ', $options['conditions']);
			$sql = "SELECT count($pk) FROM {$table} WHERE $where_str";
		} else {
			$sql = "SELECT count($pk) FROM {$table}";
		}
		$result = $db->query_and_fetch_one($sql);
		return $result["count($pk)"];
	}
	
	public static function create($params=array(), $db=null){
		global $config, $dbs;
		if(empty($db)){
			$db = $dbs[static::$dbname];
		}
		$table = static::$table;
		
		$data = array_intersect_key($params, array_flip(static::getFields($table)));
		$fields = array_keys($data);
		$data_values = array();
		foreach($data as $val){
			$data_values[] = "'" . $val . "'";
		}
		$fields = implode(',',$fields);
		$values = implode(',', $data_values);
		$sql = "INSERT INTO {$table}({$fields}) VALUES ($values)";
		$db->query($sql);
		$data['id'] = $db->insert_id();
		return static::build($data,false);
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
	}
}