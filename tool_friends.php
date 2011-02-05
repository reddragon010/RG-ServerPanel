<?php
require_once('common.php');

$sql = "SELECT * FROM `friend_token` WHERE `account_id`='{$user->userid}' AND `taken`=0";
$db_web->query($sql);

$invited_friends = array();
while($row=$db_web->fetchRow()){
	$friend = new User;
	$friend->loadUser($row['friend_id'],false);
	$f['username'] = $friend->userdata['username'];
	$f['email'] = $friend->userdata['email'];
	$invited_friends[] = $f;
}

$sql = "SELECT * FROM `account_friends` WHERE `id`='{$user->userid}' AND `expire_date`<NOW()";
$db_login->query($sql);

$friends = array();
while($row=$db_login->fetchRow()){
	$friend = new User;
	$friend->loadUser($row['friend_id'],false);
	$f['username'] = $friend->userdata['username'];
	$f['bind_date'] = $row['bind_date'];
 	$f['expire_date'] = $row['expire_date'];
	$friends[] = $f;
}

$sql = "SELECT * FROM `account_friends` WHERE `friend_id`='{$user->userid}' AND `expire_date`<NOW()";
$db_login->query($sql);

$users = array();
while($row=$db_login->fetchRow()){
	$uuser = new User;
	$uuser->loadUser($row['id'],false);
	
	$fuser['username'] = $uuser->userdata['username'];
	$fuser['bind_date'] = $row['bind_date'];
	$fuser['expire_date'] = $row['expire_date'];
	$users[] = $fuser;
}

$tpl = $twig->loadTemplate('tool_friends.tpl');
echo $tpl->render(array('invited_friends' => $invited_friends, 'friends' => $friends, 'users' => $users));
?>
