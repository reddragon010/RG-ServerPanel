<?php
require_once('common.php');

if($user->logged_in() && isset($_GET['id'])){
	$ticket = new Ticket(array('id' => $_GET['id']));
	if($user->is_admin() || ($ticket->account_id == $user->userid && ($ticket->status == 0 || $ticket->status > 2))){
		$ticket->destroy();
		flash('notice','Ticket gelöscht');
	} else {
		flash('error', 'Du hast keine Berechtigung dieses Ticket zu löschen');
	}
}
header('Location: tool_tickets.php');
?>