<?php
class Model
{
	private $_data;
	private $_new;
	private $_db;
	private $_fields;
	
	function __construct($properties=array(),$new=true)
	{
		global $config;
		$this->_data = $properties;
		$this->_new = $new;
		$this->_db = new Database($config[static::dbname]);
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
	
	public static function getFields(){
		global $config;
		$db = new Database($config[static::dbname]);
		$fields = $db->fetchFieldsArray(static::table);
		array_diff($fields, array('created_at','updated_at'));
		return $fields;
	}
	
	public static function find_all($conditions=array(), $sort=''){
		global $config;
		$db = new Database($config[static::dbname]);
		$table = static::table;
		
		if(!empty($conditions)){
			$where_str = ' WHERE ' . implode(' AND ', $conditions);
		} else {
			$where_str = '';
		}
		if(!empty($sort)){
			$order_str = " ORDER BY $sort";
		} else {
			$order_str = '';
		}
		$sql = "SELECT * FROM {$table}{$where_str}{$order_str}";
		
		$db->query($sql);
		if($db->count() > 0){
			while($row=$db->fetchRow()){
				$class_name = get_called_class();
				$obj = new $class_name($row,false);
				if(method_exists($obj,'after_find')){
					$obj->after_find();
				}
				$result[] = $obj;
			}
			return $result;
		} else {
			return false;
		}
	}
	
	public static function find($conditions=array()){
		global $config;
		$db = new Database($config[static::dbname]);
		$table = static::table;
		
		if(!empty($conditions)){
			$where_str = ' WHERE ' . implode(' AND ', $conditions);
		} else {
			$where_str = '';
		}
		
		$sql = "SELECT * FROM {$table}{$where_str} LIMIT 1";
		$db->query($sql);
		if($db->count() > 0){
			$row = $db->fetchRow();
			$class_name = get_called_class();
			$obj = new $class_name($row,false);
			if(method_exists($obj,'after_find')){
				$obj->after_find();
			}
			return $obj;
		} else {
			return false;
		}
	}
	
	public static function build($data=array()){
		
	}
	
	public static function count($conditions=array()){
		global $config;
		$db = new Database($config[static::dbname]);
		$table = static::table;
		if(!empty($conditions)){
			$where_str = implode(' AND ', $conditions);
			$sql = "SELECT count(id) FROM {$table} WHERE $where_str";
		} else {
			$sql = "SELECT count(id) FROM {$table}";
		}
		
		$db->query($sql);
		if($db->count() > 0){
			$row=$db->fetchRow();
			return $row['count(id)'];
		} else {
			return false;
		}
	}
	
	public static function create($params=array()){
		global $config;
		$db = new Database($config[static::dbname]);
		$table = static::table;
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
		$data['id'] = $db->getInsertId();
		$class_name = get_called_class();
		$result = new $class_name($data,false);
		return $result;
	}
	
	/*
	public function reload(){
		$sql = "SELECT * FROM {$this->_table} WHERE id='{$this->id}' LIMIT 1";
		
		$this->_data = $row;
		
	}
	*/
	public function destroy(){
		$table = static::table;
		$sql = "DELETE FROM {$table} WHERE id='{$this->id}'";
		$this->_db->query($sql);
		return true;
	}
	
	public function save(){
		$table = static::table;
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