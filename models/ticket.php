<?php
class Ticket
{
	var $id				=	NULL;
	var $title   	=	NULL;
	var $content 	= NULL;
	var $status 	= 0;
	var $category_id = 0;
	var $category_name = NULL;
	var $replies = array();
	var $db;
	var $new;
	var $user = NULL;
	var $realm = NULL;
	var $realm_id = NULL;
	var $character = NULL;
	var $character_id = NULL;
	var $account_id = NULL;
	var $date = NULL;
	
	var $FIELDS = array('title','content','status','category_id','account_id','realm_id','character_id');
	
	static public function get_tickets($conditions=NULL){
		global $db_web;
		if($conditions==NULL){
			$sql = "SELECT * FROM ticket_tickets";
		} else {
			$sql = "SELECT * FROM ticket_tickets WHERE $conditions";
		}
		$db_web->query($sql);
		$tickets = array();
		while($ticket = $db_web->fetchRow()){
			$tickets[] = $ticket;
		}
		foreach($tickets as $ticket){
			$tickets2[] = new Ticket($ticket);
		}
		return $tickets2;
	}
	
	function __construct($ticket,$new=false){
		global $db_web, $db_login, $realms;
		$this->new = $new;
		$this->db = $db_web;
		if(isset($ticket['id'])){
			$this->new = false;
			$this->id = $ticket['id'];
			$sql = "SELECT * FROM ticket_tickets 
							INNER JOIN ticket_categories ON ticket_tickets.category_id=ticket_categories.id
							WHERE ticket_tickets.id = {$this->id}";
			$this->db->query($sql);
			$ticket = $this->db->fetchRow();
			$replies = array();
			$sql = "SELECT * FROM ticket_replies WHERE ticket_id = {$this->id} ORDER BY date";
			$this->db->query($sql);
			while($reply = $this->db->fetchRow()){
				$tuser = new User();
				$tuser->loadUser($reply['account_id'],false);
				$reply['user'] = $tuser;
				$this->replies[] = new TicketReply($reply,false);
			}
			$this->date = $ticket['date'];
		}
		$this->realm = $realms[$ticket['realm_id']];
		$this->realm_id = $this->realm->id;
		$this->character_id = $ticket['character_id'];
		$this->character = new Character($ticket['character_id'],$ticket['realm_id']);
		$this->character->fetchData();
		$this->account_id = $ticket['account_id'];
		$this->user = new User();
		$this->user->loadUser($this->account_id,false);
		$this->title = $ticket['title'];
		$this->content = $ticket['content'];
		$this->status = $ticket['status'];
		$this->category_id = $ticket['category_id'];
		$this->category_name = $ticket['name'];
		return $this;
	}
		
	function save(){
		if($this->new){
			$sql = "INSERT INTO ticket_tickets(";
			$conj = '';
			foreach($this->FIELDS as $field){
				$sql .= $conj . $field;
				$conj = ',';
			}
			$sql .= ') VALUES (';
			$conj = '';
			foreach($this->FIELDS as $field){
				$sql .= $conj . "'" . $this->{$field} . "'";
				$conj = ',';
			}
			$sql .= ')';
			$this->db->query($sql);
			return true;
		} else {
			$sql = "UPDATE ticket_tickets SET (id='{$this->id}',";
			foreach($this->FIELDS as $field){
				$sql .= ',' . $this->{$field} . "='" . $this->$field . "'";
			}
			$sql .= ") WHERE id='{$this->id}'";
			$this->db->query($sql);
			return true;
		}
	}
	
	function destroy(){
		$sql = "DELETE FROM ticket_tickets WHERE id={$this->id}";
		$this->db->query($sql);
		return true;
	}
}