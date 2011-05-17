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
		if(count($fields) <= 1 && $fields[0] == "*"){
			$sql = "INSERT INTO {$table} VALUES ($values) $tail";
		} else {
			$sql = "INSERT INTO {$table} ($fields) VALUES ($values) $tail";
		}
		return trim($sql);
	} 
}