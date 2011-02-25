<?php
class Database {
  private $connection = NULL;
  private $result = NULL;
  private $counter=NULL;
	
	private $host 		= NULL;
 	private $username = NULL;
	private $password = NULL;
	private $db				= NULL;

  public function __construct($config){
		$this->host 		= $config['host'].':'.$config['db_port']; 	
		$this->username = $config['db_username'];
		$this->password = $config['db_password'];
		$this->db				= $config['db'];
		$this->connect();
  }
 
	private function connect(){	
		$this->connection = mysql_pconnect($this->host,$this->username,$this->password);
		if(!mysql_ping($this->connection)){
			if(!$this->connection = mysql_pconnect($this->host,$this->username,$this->password))
			 throw new Exception('MySQL Error (Connection): '.mysql_error());
		}
		if(!mysql_select_db($this->db, $this->connection))
 			throw new Exception('MySQL Error (DB-Select): ' . mysql_error());
	}
	
	public function is_connected(){
		return is_resource($this->connection);
	}
	
  public function disconnect() {
    if ($this->is_connected())				
    	mysql_close($this->connection);
  }
 
  public function query($query) {
		global $config;
		//$this->connect();
		if($config['debug']){
			if(!$this->result=mysql_query($query,$this->connection))
			 	throw new Exception('MySQL-ERROR (Query): ' . mysql_error($this->connection) . ' - on Query: ' . $query);
		} else {
			$this->result=@mysql_query($query,$this->connection);
		}
		
  	$this->counter=NULL;
  }
 
  public function fetchRow() {
  	return mysql_fetch_assoc($this->result);
  }

	public function fetchArray() {
  	return mysql_fetch_array($this->result);
  }
 
  public function count() {
  	if($this->counter==NULL && is_resource($this->result)) {
  		$this->counter=mysql_num_rows($this->result);
  	}
		return $this->counter;
  }

	public function fetchFieldsArray($table) {
		$fields = array();
		$sql="SHOW COLUMNS FROM $table";
		$result = $this->query($sql);
		if ($this->count() > 0) {
		    while ($row = $this->fetchRow()) {
		      $fields[] = $row['Field'];
		    }
		}
		return $fields;
	}

	public function getInsertId() {
		return mysql_insert_id($this->connection);
	}

	public function escape_string($string){
	  $string = mysql_real_escape_string($string);
	  $string = strip_tags($string);
	  $string = addslashes($string);
	  return $string;
	}
}
?>