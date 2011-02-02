<?php
require_once(dirname(__FILE__) . '/../common.php');

class Character{
	var $guid = NULL;
	var $data = array();
	var $fetched = false;
	
	private $chardb = NULL;
		
	public function __construct($guid){
		global $config;
		
		$this->chardb = new Database($config,$config['db']['chardb']);
		$this->guid = $guid;
	}
	
	public function fetchData(){
		$sql="SELECT * FROM `characters` WHERE `guid`=".$this->guid." LIMIT 1";
		$this->chardb->query($sql);
		if($this->chardb->count() > 0){
			$char = $this->chardb->fetchRow();
			$this->data['name']					= $char['name'];
			$this->data['level'] 				= $char['level'];
			$this->data['race'] 				= $char['race'];
			$this->data['class'] 				= $char['class'];
			$this->data['gender'] 			= $char['gender'];
			$this->data['money'] 				= $char['money'];
			$this->data['online'] 			= $char['online'];
			$this->data['flags'] 	= $char['playerFlags'];
			
			$this->fetched = true;
			return true;
		} else {
			return false;
		}
	}
	
	public function writeData($writedata){
		$sql1 = "UPDATE `characters` SET";
		$sql2 = "";
		foreach($this->data as $key => $value){
			if(isset($writedata[$key])){
				$sql2 = $sql2 . ' `'.$key."` = '".$writedata[$key]."'";
				$this->data[$key]=$writedata[$key];
			}
		}
		$sql3 = " WHERE `guid`=".$this->guid;
		if(!empty($sql2)){
			$this->chardb->query($sql1.$sql2.$sql3);
			return true;
		}
		return false;
	}
}

?>