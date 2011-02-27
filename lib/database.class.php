<?php
class Database {
	static $dbconns = array();
	
  private $connection = NULL;
  private $result 	= NULL;
  private $counter	= NULL;
	
	private $host 		= NULL;
 	private $username = NULL;
	private $password = NULL;
	private $db				= NULL;

  public function __construct($config){
		$this->host 		= $config['host'].':'.$config['db_port']; 	
		$this->username = $config['db_username'];
		$this->password = $config['db_password'];
		$this->db				= $config['db'];
  }
 
	private function connect($force=false){	
		if(isset($this->dbconns[$this->db])){
			$this->connection = $this->dbconns[$this->db];
		}
		if(!$this->is_connected() && !$force){
			$this->connection = mysql_connect($this->host,$this->username,$this->password,true);
			if(!mysql_ping($this->connection)){
				if(!$this->connection = mysql_connect($this->host,$this->username,$this->password,true))
				 throw new Exception('MySQL Error (Connection): '.mysql_error());
			}
			if(!mysql_select_db($this->db, $this->connection))
	 			throw new Exception('MySQL Error (DB-Select): ' . mysql_error());
			$this->dbconns[$this->db] = $this->connection;
		}
	}
	
	public function is_connected(){
		return is_resource($this->connection);
	}
	
  public function disconnect() {
    if ($this->is_connected())				
    	mysql_close($this->connection);
  }
 
  public function query($query) {
		$this->connect();
		if(!$this->result=mysql_query($query,$this->connection))
			 throw new Exception('MySQL-ERROR (Query): ' . mysql_error($this->connection));
				
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
		$this->connect();
	  $string = mysql_real_escape_string($string);
	  $string = strip_tags($string);
	  $string = addslashes($string);
	  return $string;
	}
	
	public function __destruct(){
		$this->disconnect();
	}
}
?>