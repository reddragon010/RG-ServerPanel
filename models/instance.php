<?php
/**
* 
*/
class Instance extends Model
{
	const table = 'instances';
	const dbname = 'web';
	
	public static function find_all()
	{
		global $config;
		$db = new Database($config[static::dbname]);
		
		$sql = "SELECT * FROM instances";
		$db->query($sql);
		$instances = array();
		while($ini = $db->fetchRow()){
			$sql = "SELECT *,UNIX_TIMESTAMP(test_start),UNIX_TIMESTAMP(test_end) FROM bosses WHERE instance_id={$ini['id']}";
			$result2 = $db->query($sql);
			$ini['status'] = 0;
			while($boss = $db->fetchRow()){
				$ini['bosses'][] = $boss;
				$ini['status'] += $boss['status']; 
			}
			if(isset($ini['bosses'])){
				$ini['bosses_count'] = count($ini['bosses']);
			} else {
				$ini['bosses_count'] = 0;
			}
			$instances[] = $ini;
		}
		return $instances;
	}
}
