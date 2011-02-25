<?php
/**
* 
*/
class Boss extends Model
{
	const table = 'bosses';
	const dbname = 'web';
	
	public static function find_running_tests(){
		global $config;
		$db = new Database($config[static::dbname]);
		
		$sql = "SELECT *, UNIX_TIMESTAMP(test_start), UNIX_TIMESTAMP(test_end) FROM `bosses` WHERE test_start < NOW() AND test_end > NOW()";
		$db->query($sql);
		$running_tests = array();
		while($boss = $db->fetchRow()){
			$boss['start'] = date('d.m.o H:i' ,$boss['UNIX_TIMESTAMP(test_start)']);
			$boss['end'] = date('d.m.o H:i' ,$boss['UNIX_TIMESTAMP(test_end)']);
			$running_tests[] = $boss;
		}
		return $running_tests;
	}
	
	public static function find_upcoming_tests(){
		global $config;
		$db = new Database($config[static::dbname]);
		
		$sql = "SELECT *, UNIX_TIMESTAMP(test_start), UNIX_TIMESTAMP(test_end) FROM `bosses` WHERE test_start > NOW()";
		$db->query($sql);
		$upcoming_tests = array();
		while($boss = $boss = $db->fetchRow()){
			$boss['start'] = date('d.m.o H:i' ,$boss['UNIX_TIMESTAMP(test_start)']);
			$boss['end'] = date('d.m.o H:i' ,$boss['UNIX_TIMESTAMP(test_end)']);
			$upcoming_tests[] = $boss;
		}
		return $upcoming_tests;
	}
	
	
}
