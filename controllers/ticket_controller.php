<?php
/**
* 
*/
class ticket_controller extends Controller
{
	
	function index(){
		global $user;
		$tickets = array();
		if($user->logged_in()){
			if(!$user->is_admin()){
				$base_cond = "account_id = {$user->userid} AND ";
				
			} else {
				$base_cond = "";	
			}
			$new_tickets = Ticket::find_all(array($base_cond . "status = 0"));
			$open_tickets = Ticket::find_all(array($base_cond . "status > 0 AND status < 3"));
			$closed_tickets = Ticket::find_all(array($base_cond . "status = 3 ORDER BY updated_at LIMIT 10"));
			$this->render('tool_tickets.tpl',array( 'new_tickets' => $new_tickets, 'open_tickets' => $open_tickets, 'closed_tickets' => $closed_tickets));
		} else {
			$this->render();
		}
		
	}
	
	function add(){
		global $realms, $user;
		$realm_names = array();
		foreach($realms as $realm){
			$realm_names[$realm->id] = $realm->name;
		}
		
		$ticket = new Ticket();
		$categories = Ticket::getCategories();
		$characters = array();
		$user->fetchChars();
		foreach($user->chars as $character){
			$characters[$character->guid] = $character->data['name'];
		}
		$main = $user->fetchMainChar();
		if($main){
			$characters[$main->guid] = $main->data['name'];
		}
		$this->render(array('characters' => $characters, 'realms' => $realm_names, 'categories' => $categories,));
	}
	
	function create($params){
		global $user;
		if(!empty($params['category_id']) && !empty($params['realm_id']) && !empty($params['title']) && !empty($params['content'])){
			$ticket['character_id'] = $params['character_id'];
			$ticket['category_id'] = $params['category_id'];
			$ticket['realm_id'] = $params['realm_id'];
			$ticket['title'] = $params['title'];
			$ticket['content'] = $params['content'];
			$ticket['account_id'] = $user->userid;
			$ticket['status'] = 0;
			$t = new Ticket($ticket,true);
			if($t->save()){
				$this->render_ajax('success', 'OK');
			} else {
				$this->render_ajax('error', 'Fehler beim Speichern');
			}
		} else {
			$this->render_ajax('error', 'Bitte alle Felder ausfüllen');
		}
	}
	
	function delete(){
		global $user;
		if($user->logged_in() && isset($_GET['id'])){
			$ticket = new Ticket(array('id' => $_GET['id']));
			if($user->is_admin() || ($ticket->account_id == $user->userid && ($ticket->status == 0 || $ticket->status > 2))){
				$ticket->destroy();
				$this->flash('notice','Ticket gelöscht');
			} else {
				$this->flash('error', 'Du hast keine Berechtigung dieses Ticket zu löschen');
			}
		}
		$this->redirect_to('ticket','index');
	}
}
