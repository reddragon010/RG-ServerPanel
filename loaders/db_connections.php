<?php
$dbs = array();
foreach($config['db'] as $dbname=>$db){
	if(is_array($db)){
		foreach($db as $index => $db_url){
			$dbs[$dbname . $index] = Database::instance($db_url);
		}
	} else {
		$dbs[$dbname] = Database::instance($db);
	}
}