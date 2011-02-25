<?php
class Character{
	var $guid = NULL;
	var $data = array();
	var $fetched = false;
	var $realm_id = NULL;
	var $realm = NULL;
	var $gm = false;
	var $db = NULL;
	
	const CHAR_DATA_FIELDS = "`name`, `level`, `race`, `class`, `gender`, `money`, `online`, `map`, `zone`, `totaltime`";
	
	/**
	 * Creates a character-object from a char-id.
	 * The character is not fully loaded until it gets fetched from the database. Use the fetchData-function
	 *
	 * @param int $guid guid of a char
	 * @return void
	 * @author Michael Riedmann
	 **/
	public function __construct($guid,$realm_id){
		global $realms;
		$this->guid = $guid;
		$this->realm_id = $realm_id;
		$this->realm = $realms[$this->realm_id];
		$this->db = $this->realm->db;
	}
	
	/**
	 * fatching character's data from database
	 *
	 * @return bool
	 * @author Michael Riedmann
	 **/
	public function fetchData(){
		if(isset($this->guid)){
			$sql="SELECT ".self::CHAR_DATA_FIELDS." FROM `characters` WHERE `guid`=".$this->guid." LIMIT 1";
			$this->db->query($sql);
			if($this->db->count() > 0){
				$char = $this->db->fetchRow();
				$this->data	= $char;
				$this->fetched = true;
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * writes data to the character
	 * 
	 * @param array $writedata Array-Format: 'db_field' => 'value'
	 * @return bool
	 * @author Michael Riedmann
	 **/
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
			$this->db->query($sql1.$sql2.$sql3);
			return true;
		}
		return false;
	}
}

?>