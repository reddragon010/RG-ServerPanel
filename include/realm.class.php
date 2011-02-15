<?php
require_once(dirname(__FILE__) . '/../common.php');

class Realm {
	var $online	= NULL;
	
	var $id = NULL;
	var $ip	= NULL;
	var $port = NULL;
	var $name = NULL;
	var $uptime = NULL;
	
	var $db = NULL;
	
	var $online_chars = NULL;
	var $online_chars_count = NULL;
	var $online_ally_chars_count = NULL;
	var $online_horde_chars_count = NULL;
	var $online_gm_chars = NULL;
	var $online_gm_chars_count = NULL;
		
	public function __construct($id){
		global $config;
		
		$this->id = $id;
		$this->ip = $config['realm'][$id]['host'];
		$this->port = $config['realm'][$id]['port'];
		$this->name = $config['realm'][$id]['name'];
		$this->db = new Database($config['realm'][$id]);
	}
	
	function get_status($force=false){
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

	function get_uptime(){
		global $db_login;
		
		if($this->uptime == NULL){
	  	$sql = "SELECT * FROM `uptime` ORDER BY `starttime` DESC LIMIT 1"; 
	 		$db_login->query($sql);
	  	$uptime_results = $db_login->fetchRow();    
	  	$this->uptime = $uptime_results['uptime'];
		}
		return $this->uptime;
	}
	
	function get_online_chars($sort="`name` ASC",$conditions=array()){
		if($this->online_chars == NULL){
			$sql = "SELECT `guid`, ".Character::CHAR_DATA_FIELDS." FROM `characters` WHERE `online`='1'";
			foreach($conditions as $val){
				$sql .= " AND $val";
			}
			$sql .= " ORDER BY $sort";
			$this->db->query($sql);
			$chars = array();
			if($this->db->count() > 0){
				while($row=$this->db->fetchRow()){
					$char = new Character($row['guid'], $this->id);
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

	function get_online_chars_count(){
		if($this->online_chars_count == NULL){
	  	$sql = "SELECT count(online) FROM `characters` WHERE `online` = 1";
	  	$this->db->query($sql);
	  	$row = $this->db->fetchRow();
			$this->online_chars_count = $row["count(online)"];
		}
	  return $this->online_chars_count;
	}

	function get_online_horde_chars_count(){
		global $HORDE;
		
		if($this->online_horde_chars_count == NULL){
	  	$sql = "SELECT count(online) FROM `characters` WHERE `online` = 1 AND `race` IN (".implode(',' , $HORDE).")";
	  	$this->db->query($sql);
	  	$row = $this->db->fetchRow();
	  	$this->online_horde_chars_count = $row["count(online)"];
		}
		return $this->online_horde_chars_count;
	}

	function get_online_ally_chars_count(){
		global $ALLIANCE;
		
		if($this->online_ally_chars_count == NULL){
	  	$sql = "SELECT count(online) FROM `characters` WHERE `online` = 1 AND `race` IN (".implode(',' , $ALLIANCE).")";
	  	$this->db->query($sql);
	  	$row = $this->db->fetchRow();
			$this->online_ally_chars_count = $row["count(online)"];
		}
	  return $this->online_ally_chars_count;
	}
	
	function get_online_gm_chars($gmlevel=1){
		global $config;
		if($this->online_gm_chars == NULL){
			$sql = "SELECT `guid`, ".Character::CHAR_DATA_FIELDS." FROM `characters` WHERE account IN (SELECT id FROM {$config['login']['db']}.account WHERE gmlevel > $gmlevel AND online = 1)";
			$this->db->query($sql);
			$chars = array();
			if($this->db->count() > 0){
				while($row=$this->db->fetchRow()){
					$char = new Character($row['guid'], $this->id);
					unset($row['guid']);
					$char->data = $row;
					$char->gm = true;
					$chars[] = $char;
				}
			}
			$this->online_gm_chars = $chars;
		}
		return $this->online_gm_chars;
	}
	
	function get_online_gm_chars_count($gmlevel=1){
		if($this->online_gm_chars_count == NULL){
			$this->online_gm_chars_count = count($this->get_online_gm_chars($gmlevel));
		}
		return $this->online_gm_chars_count;
	}
	
	function get_user_chars($user_id,$conditions=array(),$main_id=NULL){
		if($main_id==NULL){		
			$sql = "SELECT `guid`, ".Character::CHAR_DATA_FIELDS." FROM `characters` WHERE `account`='$user_id'";
		} else {
			$sql = "SELECT `guid`, ".Character::CHAR_DATA_FIELDS." FROM `characters` WHERE `account`='$user_id' AND `guid`!=$main_id ";
		}
		foreach($conditions as $val){
			$sql .= " AND $val";
		}
		$this->db->query($sql);
		$chars = array();
		if($this->db->count() > 0){
			while($row=$this->db->fetchRow()){
				$char = new Character($row['guid'], $this->id);
				unset($row['guid']);
				$char->data = $row;
				$char->realm_id = $this->id;
				$chars[] = $char;
			}
		}
		return $chars;
	}
}
