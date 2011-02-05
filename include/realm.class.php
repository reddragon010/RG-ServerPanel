<?php
require_once(dirname(__FILE__) . '/../common.php');

class Realm {
	var $online	= NULL;
	
	var $id = NULL;
	var $ip	= NULL;
	var $port = NULL;
	var $name = NULL;
	
	var $online_chars = NULL;
	var $online_chars_count = NULL;
	var $online_ally_chars_count = NULL;
	var $online_horde_chars_count = NULL;
	var $uptime = NULL;
		
	public function __construct($id){
		global $config;
		
		$this->id = $id;
		$this->ip = $config['realms'][$id]['ip'];
		$this->port = $config['realms'][$id]['port'];
		$this->name = $config['realms'][$id]['name'];
	}
	
	function getStatus($force=false){
		global $config;

		if($this->online == NULL || $force){
			if (! $sock = @fsockopen($this->ip, $this->port, $num, $error, 3)) 
	    	$this->online = true;
	  	else{ 
	    	$this->online = false;
	    	fclose($sock); 
	  	}
		}
		return $this->online;
	}

	function getUptime(){
		global $db_realm;
		
		if($this->uptime == NULL){
	  	$sql = "SELECT * FROM `uptime` ORDER BY `starttime` DESC LIMIT 1"; 
	 		$db_realm->query($sql);
	  	$uptime_results = $db_realm->fetchRow();    
	  	$this->uptime = $uptime_results['uptime'];
		}
		return $this->uptime;
	}
	
	function getOnlineChars($conditions=array()){
		global $db_chars;
		
		if($this->online_chars == NULL){
			$sql = "SELECT `guid`, ".Character::CHAR_DATA_FIELDS." FROM `characters` WHERE `online`='1'";
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
					$char->realm_id = $this->id;
					$chars[] = $char;
				}
			}
			$this->online_chars = $chars;
		}
		return $this->online_chars;
	}

	function getOnlineCharsCount(){
		global $db_chars;
		
		if($this->online_chars_count == NULL){
	  	$sql = "SELECT count(online) FROM `characters` WHERE `online` = 1";
	  	$db_chars->query($sql);
	  	$row = $db_chars->fetchRow();
			$this->online_chars_count = $row["count(online)"];
		}
	  return $this->online_chars_count;
	}

	function getOnlineHordeCharsCount(){
		global $db_chars, $HORDE;
		
		if($this->online_horde_chars_count){
	  	$sql = "SELECT count(online) FROM `characters` WHERE `online` = 1 AND `race` IN ($HORDE)";
	  	$db_chars->query($sql);
	  	$row = $db_chars->fetchRow;
	  	$this->online_horde_chars_count = $row["count(online)"];
		}
		return $this->online_horde_chars_count;
	}

	function getOnlineAllyCharsCount(){
		global $db_chars, $ALLY;
		
		if($this->online_ally_chars_count){
	  	$sql = "SELECT Count(Online) FROM `characters` WHERE `online` = 1 AND `race` IN ($ALLY)";
	  	$db_chars->query($sql);
	  	$row = $db_chars->fetchRow;
			$this->online_ally_chars_count = $row["count(online)"];
		}
	  return $this->online_ally_chars_count;
	}
	
}
