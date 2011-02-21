<?php
require_once('common.php');

if(!empty($_POST)){
	if(!empty($_POST['category_id']) && !empty($_POST['realm_id']) && !empty($_POST['title']) && !empty($_POST['content'])){
		$ticket['character_id'] = $_POST['character_id'];
		$ticket['category_id'] = $_POST['category_id'];
		$ticket['realm_id'] = $_POST['realm_id'];
		$ticket['title'] = $_POST['title'];
		$ticket['content'] = $_POST['content'];
		$ticket['account_id'] = $user->userid;
		$ticket['status'] = 0;
		$t = new Ticket($ticket,true);
		if($t->save()){
			return_ajax('success', 'OK');
		} else {
			return_ajax('error', 'Fehler beim Speichern');
		}
	} else {
		return_ajax('error', 'Bitte alle Felder ausfüllen');
	}
} else {
	$realm_names = array();
	foreach($realms as $realm){
		$realm_names[$realm->id] = $realm->name;
	}

	$sql = "SELECT * FROM ticket_categories";
	$db_web->query($sql);
	$categories = array();
	while($category = $db_web->fetchRow()){
		$categories[$category['id']] = $category['name'];
	}

	$characters = array();
	$user->fetchChars();
	foreach($user->chars as $character){
		$characters[$character->id] = $character->data->name;
	}
	$main = $user->fetchMainChar();
	if($main){
		$characters[$main->guid] = $main->data['name'];
	}

	$tpl = $twig->loadTemplate('tool_tickets_new.tpl');
	echo $tpl->render(array('characters' => $characters, 'realms' => $realm_names, 'categories' => $categories,));
}
?>