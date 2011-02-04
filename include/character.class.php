<?php
require_once(dirname(__FILE__) . '/../common.php');

class Character{
	var $guid = NULL;
	var $data = array();
	var $fetched = false;
	
	const CHAR_DATA_FIELDS = "`name`, `level`, `race`, `class`, `gender`, `money`, `online`, `map`, `totaltime`";
	
	/**
	 * fetches all online characters from the database
	 *
	 * @return array an array of char-objects
	 * @author Michael Riedmann
	 **/
	public static function get_online_chars($conditions=array()){
		global $db_chars;
		
		$sql = "SELECT `guid`, ".self::CHAR_DATA_FIELDS." FROM `characters` WHERE `online`='1'";
		foreach($conditions as $val){
			$sql .= " AND $val";
		}
		$sql .= " ORDER BY `name`";
		$db_chars->query($sql);
		$chars = array();
		if($db_chars->count() > 0){
			while($row=$db_chars->fetchRow()){
				$char = new Character($row['guid']);
				unset($row['guid']);
				$char->data = $row;
				$chars[] = $char;
			}
		}
		return $chars;
	}
	
	public static function get_online_gm_chars($gmlevel=1){
		global $db_chars, $config;
		$sql = "SELECT `guid`, ".self::CHAR_DATA_FIELDS." FROM `characters` WHERE account IN (SELECT id FROM {$config['db']['realmdb']}.account WHERE gmlevel > $gmlevel)";
		$db_chars->query($sql);
		$chars = array();
		if($db_chars->count() > 0){
			while($row=$db_chars->fetchRow()){
				$char = new Character($row['guid']);
				unset($row['guid']);
				$char->data = $row;
				$chars[] = $char;
			}
		}
		return $chars;
	}
	
	public static function get_online_chars_count($conditions=array()){
		global $db_chars;
		
		$sql = "SELECT count(guid) FROM `characters` WHERE `online`='1'";
		foreach($conditions as $val){
			$sql .= " AND $val";
		}
		$db_chars->query($sql);
		$row = $db_chars->fetchRow();
		return $row['count(guid)'];
	}
	
	/**
	 * Creates a character-object from a char-id.
	 * The character is not fully loaded until it gets fetched from the database. Use the fetchData-function
	 *
	 * @param int $guid guid of a char
	 * @return void
	 * @author Michael Riedmann
	 **/
	public function __construct($guid){
		$this->guid = $guid;
	}
	
	/**
	 * fatching character's data from database
	 *
	 * @return bool
	 * @author Michael Riedmann
	 **/
	public function fetchData(){
		global $db_chars;
		
		$sql="SELECT ".self::CHAR_DATA_FIELDS." FROM `characters` WHERE `guid`=".$this->guid." LIMIT 1";
		$db_chars->query($sql);
		if($db_chars->count() > 0){
			$char = $db_chars->fetchRow();
			$this->data	= $char;
			$this->fetched = true;
			return true;
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
		global $db_chars;
		
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
			$db_chars->query($sql1.$sql2.$sql3);
			return true;
		}
		return false;
	}
}

?>