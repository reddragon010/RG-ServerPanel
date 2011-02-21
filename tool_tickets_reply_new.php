<?php
require_once('common.php');

if(!empty($_POST)){
	if(!empty($_POST['content']) && !empty($_POST['id'])){
		$t = new Ticket(array('id' => $_POST['id']));
		if($user->is_admin()){
			$status = $_POST['status'];
		} else {
			if($t->status >= 0 && $t->status < 3){
				$status = $t->status;
			} else {
				return_ajax('error', 'Ticket ist bereits abgeschlossen. Bitte erstelle ein neues');
			}
		}
		if($t->new_reply($_POST['content'],$user,$status)){
			return_ajax('success', 'OK');
		} else {
			return_ajax('error', 'Fehler beim Speichern');
		}
	} else {
		return_ajax('error', 'Bitte alle Felder ausfüllen');
	}
} else {


	if(isset($_GET['id'])){
		$ticket = new Ticket(array('id' => $_GET['id']));
	$tpl = $twig->loadTemplate('tool_tickets_reply_new.tpl');
	echo $tpl->render(array('ticket' => $ticket));
	} else {
		header('Location: index.php');
	}
}
?>