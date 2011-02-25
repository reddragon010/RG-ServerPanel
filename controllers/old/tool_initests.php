<?php
require_once('common.php');

//Load Running Tests
$sql = "SELECT *, UNIX_TIMESTAMP(test_start), UNIX_TIMESTAMP(test_end) FROM `bosses` WHERE test_start < NOW() AND test_end > NOW()";
$db_web->query($sql);
$running_tests = array();
while($boss = $db_web->fetchRow()){
	$boss['start'] = date('d.m.o H:i' ,$boss['UNIX_TIMESTAMP(test_start)']);
	$boss['end'] = date('d.m.o H:i' ,$boss['UNIX_TIMESTAMP(test_end)']);
	$running_tests[] = $boss;
}

//Load Upcoming Tests
$sql = "SELECT *, UNIX_TIMESTAMP(test_start), UNIX_TIMESTAMP(test_end) FROM `bosses` WHERE test_start > NOW()";
$db_web->query($sql);
$upcoming_tests = array();
while($boss = $boss = $db_web->fetchRow()){
	$boss['start'] = date('d.m.o H:i' ,$boss['UNIX_TIMESTAMP(test_start)']);
	$boss['end'] = date('d.m.o H:i' ,$boss['UNIX_TIMESTAMP(test_end)']);
	$upcoming_tests[] = $boss;
}

//Load Ini Data
$sql = "SELECT * FROM instances";
$db_web->query($sql);
$instances = array();
while($ini = $db_web->fetchRow()){
	$sql = "SELECT *,UNIX_TIMESTAMP(test_start),UNIX_TIMESTAMP(test_end) FROM bosses WHERE instance_id={$ini['id']}";
	$result2 = mysql_query($sql) or die("Query Error: ".mysql_error());
	$ini['status'] = 0;
	while($boss = mysql_fetch_assoc($result2)){
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

$tpl = $twig->loadTemplate('tool_initests.tpl');
echo $tpl->render(array('running_tests' => $running_tests, 'upcoming_tests' => $upcoming_tests, 'instances' => $instances));
?>