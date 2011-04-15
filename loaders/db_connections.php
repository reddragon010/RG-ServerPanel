<?php
$dbs = array();
$dbs['web'] = Database::instance($config['db']['web']);
$dbs['login'] = Database::instance($config['db']['login']);
foreach($config['db']['realm'] as $index => $db_url){
	$dbs['realm' . $index] = Database::instance($db_url);
}