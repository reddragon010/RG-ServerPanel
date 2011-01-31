<?php
class Database {
  private $connection = NULL;
  private $result = NULL;
  private $counter=NULL;
 
 
  public function __construct($config,$db){
		$this->connection = mysql_connect($config['db']['host'],$config['db']['username'],$config['db']['password'],TRUE) or die('Connection failed: ' . mysql_error());
  	mysql_select_db($db, $this->connection) or die('SelectDatabase failed: ' . mysql_error());
  }
 
  public function disconnect() {
    if (is_resource($this->connection))				
    	mysql_close($this->connection);
  }
 
  public function query($query) {
  	$this->result=mysql_query($query,$this->connection);
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
}
?>