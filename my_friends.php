<?php
require_once('include/common.inc.php');

$realmdb = new Database($config,$config['db']['realmdb']);
$webdb = new Database($config,$config['db']['webdb']);
?>
<a href="index.php?a=friend_invite">Einen Freund einladen</a>

<?php
$sql = "SELECT * FROM `friend_token` WHERE `account_id`='{$user->userid}' AND `taken`=0";
$webdb->query($sql);
?>
<h3>Ausgeschickte Anfragen</h3>
<table style="width:100%">
	<tr>
		<th>Freund</th>
		<th>E-Mail</th>
	</tr>
<?php while($row=$webdb->fetchRow()){ ?>
	<?php
		$friend = new User;
		$friend->loadUser($row['friend_id'],false);
	?>
	<tr>
		<td><?php echo $friend->userdata['username'] ?></td>
		<td><?php echo $friend->userdata['email'] ?></td>
	</tr>
<?php } ?>
</table>

<?php
$sql = "SELECT * FROM `account_friends` WHERE `id`='{$user->userid}' AND `expire_date`<NOW()";
$realmdb->query($sql);
?>
<h3>Ausgehende Freundschaften</h3>
<table style="width:100%">
	<tr>
		<th>Freund</th>
		<th>von</th>
		<th>bis</th>
	</tr>
<?php while($row=$realmdb->fetchRow()){ ?>
	<?php
		$friend = new User;
		$friend->loadUser($row['friend_id'],false);
	?>
	<tr>
		<td><?php echo $friend->userdata['username'] ?></td>
		<td><?php echo $row['bind_date'] ?></td>
		<td><?php echo $row['expire_date'] ?></td>
	</tr>
<?php } ?>
</table>

<?php
$sql = "SELECT * FROM `account_friends` WHERE `friend_id`='{$user->userid}' AND `expire_date`<NOW()";
$realmdb->query($sql);
?>
<h3>Eingehende Freundschaften</h3>
<table style="width:100%">
	<tr>
		<th>Freund</th>
		<th>von</th>
		<th>bis</th>
	</tr>
<?php while($row=$realmdb->fetchRow()){ ?>
	<?php
		$uuser = new User;
		$uuser->loadUser($row['id'],false);
	?>
	<tr>
		<td><?php echo $uuser->userdata['username'] ?></td>
		<td><?php echo $row['bind_date'] ?></td>
		<td><?php echo $row['expire_date'] ?></td>
	</tr>
<?php } ?>
</table>