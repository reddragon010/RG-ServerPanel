<?php
require_once('include/common.inc.php');
require_once('include/functions_friendsys.php');

$friend = new User;
$friend->loadUser(3,false);

//print_r($friend);
echo '<br/>';
//print_r($user);
if($user->send_friend_invite($friend)){
	echo '<br/>';
  $friend->use_friend_token($user->token);
	echo '<br/>';
}
print_r(flushflash());

?>