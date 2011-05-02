<?php
class SQLUpdate extends SQLQuery
{
	private $values=array();
	
	public function set($values){
		$this->values = $values;
	}
	
	protected function build(){
		$table = $this->table;
		$set_vals = array();
		foreach($this->values as $key=>$val){
			if(in_array($key,$this->fields))
				$set_vals[] = "$key='$val'";
		}
		$sets = implode(',', $set_vals);
		$tail = implode(' ', array($this->where));
		$sql = "UPDATE {$table} SET ($sets) $tail";
		return $sql;
	}
}