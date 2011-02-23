<?php
/**
* 
*/
class ticket_controller extends Controller
{
	
	function index(){
		$tickets = array();
		if($user->logged_in()){
			if(!$user->is_admin()){
				$base_cond = "account_id = {$user->userid} AND ";
			} else {
				$base_cond = "";	
			}
			$new_tickets = Ticket::get_tickets($base_cond . "status = 0");
			$open_tickets = Ticket::get_tickets($base_cond . "status > 0 AND status < 3");
			$closed_tickets = Ticket::get_tickets($base_cond . "status = 3 ORDER BY date LIMIT 10");
		}
		$tpl = $twig->loadTemplate('tool_tickets.tpl');
		echo $tpl->render(array('new_tickets' => $new_tickets, 'open_tickets' => $open_tickets, 'closed_tickets' => $closed_tickets));
	}
	
	function add(){
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
			$characters[$character->guid] = $character->data['name'];
		}
		$main = $user->fetchMainChar();
		if($main){
			$characters[$main->guid] = $main->data['name'];
		}
		$tpl = $twig->loadTemplate('tool_tickets_new.tpl');
		echo $tpl->render(array('characters' => $characters, 'realms' => $realm_names, 'categories' => $categories,));
	}
	
	function create(){
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
	}
	
	function delete(){
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
	}
}
