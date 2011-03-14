<?php
/**
* 
*/
class ticketreply_controller extends Controller
{
	
	function add() {
		if(isset($_GET['id'])){
			$ticket = new Ticket(array('id' => $_GET['id']));
			$this->render('tool_tickets_reply_new.tpl',array('ticket' => $ticket));
		} else {
			$this->redirect_to('ticket','index');
		}
	}
	
	function create($params) {
		global $user;
		if(!$user->logged_in()){
			$this->flash('error', 'Bitte einloggen');
			$this->redirect_to('news', 'index');
		} else {
			$params['account_id'] = $user->userid;
		}
		if(!empty($params['content']) && !empty($params['ticket_id'])){
			if($user->is_admin()){
				$status = $params['status'];
			} else {
				if($t->status >= 0 && $t->status < 3){
					$status = $t->status;
				} else {
					$this->render_ajax('error', 'Ticket ist bereits abgeschlossen. Bitte erstelle ein neues');
				}
			}
			if(Ticketreply::create($params)){
				$this->render_ajax('success', 'OK');
			} else {
				$this->render_ajax('error', 'Fehler beim Speichern');
			}
		} else {
			$this->render_ajax('error', 'Bitte alle Felder ausfüllen');
		}	
	}
}
