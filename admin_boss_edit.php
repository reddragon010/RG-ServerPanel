<?php
require_once('common.php');

if(!empty($_POST)){
	if(empty($_POST['test_start']) || empty($_POST['test_end'])){
		$_POST['test_start'] = '0000-00-00 00:00:00';
		$_POST['test_end'] = '0000-00-00 00:00:00';
	}
	$sql = "UPDATE `bosses` 
					SET name='{$_POST['name']}',
							test_start='{$_POST['test_start']}',
							test_end='{$_POST['test_end']}',
							comment='{$_POST['comment']}',
							status='{$_POST['status']}' 
					WHERE id={$_POST['id']}";
	if(!$db_web->query($sql)){
		return_ajax('error', mysql_error());
		exit();
	} else {
		return_ajax('success', 'DONE!');
	}	
} else {
	$sql = "SELECT * FROM bosses WHERE id={$_GET['id']} LIMIT 1";
	$db_web->query($sql);
	$boss = $db_web->fetchRow();
	$tpl = $twig->loadTemplate('admin_boss_edit.tpl');
	echo $tpl->render(array());
} ?>