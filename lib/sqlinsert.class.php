<?php
class SQLInsert extends SQLQuery
{
	private $values=array();
	
	public function values($values){
		$this->values = $values;
	}
	
	protected function build(){
		$fields = implode(',', $this->fields);
		$table = $this->table;
		$values = implode(',', $this->values);
		$tail = implode(' ', array($this->where));
		$sql = "INSERT INTO {$table} ($fields) VALUES ($values) $tail";
		return $sql;
	} 
}