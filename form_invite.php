<?php
if(isset($_POST['invite_email'])){
	if(!empty($_POST['invite_email'])){
		$friend_id = userid_by_email($_POST['invite_email']);
		if($friend_id){
			$friend = new User;
			$friend->loadUser($friend_id,false);
			if($user->send_friend_invite($friend)){
				flash('success', 'E-Mail wurde verschickt');
			} else {
				flash('error', 'E-Mail konnte nicht gesendet werden');
			}
		} else {
			flash('error', 'E-Mail Adresse konnte nicht gefunden werden');
		}
	}
}
?>
<div id="invite">
	<form method="post" id="invite_form" name="form" action="index.php?a=friend_invite">
		E-Mail des Freunds <input type="text" name="invite_email">
		<input id="submit" type="submit" name="submit" value="Abschicken">
	</form>
</div>