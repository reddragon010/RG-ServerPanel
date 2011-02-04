<?php
require_once('common.php');

$realmdb = new Database($config,$config['db']['realmdb']);
$webdb = new Database($config,$config['db']['webdb']);

$sql = "SELECT * FROM `friend_token` WHERE `account_id`='{$user->userid}' AND `taken`=0";
$webdb->query($sql);

$invited_friends = array();
while($row=$webdb->fetchRow()){
	$friend = new User;
	$friend->loadUser($row['friend_id'],false);
	$f['username'] = $friend->userdata['username'];
	$f['email'] = $friend->userdata['email'];
	$invited_friends[] = $f;
}

$sql = "SELECT * FROM `account_friends` WHERE `id`='{$user->userid}' AND `expire_date`<NOW()";
$realmdb->query($sql);

$friends = array();
while($row=$realmdb->fetchRow()){
	$friend = new User;
	$friend->loadUser($row['friend_id'],false);
	$f['username'] = $friend->userdata['username'];
	$f['bind_date'] = $row['bind_date'];
 	$f['expire_date'] = $row['expire_date'];
	$friends[] = $f;
}

$sql = "SELECT * FROM `account_friends` WHERE `friend_id`='{$user->userid}' AND `expire_date`<NOW()";
$realmdb->query($sql);

$users = array();
while($row=$realmdb->fetchRow()){
	$uuser = new User;
	$uuser->loadUser($row['id'],false);
	
	$fuser['username'] = $uuser->userdata['username'];
	$fuser['bind_date'] = $row['bind_date'];
	$fuser['expire_date'] = $row['expire_date'];
	$users[] = $fuser;
}

$tpl = $twig->loadTemplate('my_friends.tpl');
echo $tpl->render(array('invited_friends' => $invited_friends, 'friends' => $friends, 'users' => $users));
?>
