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
  }
 
	private function connect(){
		if(!$this->is_connected()){
			$this->connection = mysql_connect($this->host ,$this->username,$this->password,TRUE) or die('Connection failed: ' . mysql_error());
  		mysql_select_db($this->db, $this->connection) or die('Select Database failed: ' . mysql_error());
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
  	$this->result=mysql_query($query,$this->connection) or die('SQL-ERROR: ' . mysql_error());
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

	public function escape_string($string){
		$this->connect();
	  $string = mysql_real_escape_string($string);
	  $string = strip_tags($string);
	  $string = addslashes($string);
	  return $string;
	}
}
?>