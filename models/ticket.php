<?php
class Ticket extends Model
{
	var $category_name = NULL;
	var $replies = array();
	var $user = NULL;
	var $realm = NULL;
	var $character = NULL;
	
	const table = 'ticket_tickets';
	const dbname = 'web';
	
	function after_find(){
		global $realms;
		//get User
		$this->user = new User();
		$this->user->loadUser($this->account_id,false);
		//get Character
		$this->character = new Character($this->character_id,$this->realm_id);
		$this->character->fetchData();
		//get Category Name
		$sql = "SELECT name FROM ticket_categories WHERE id={$this->category_id}";
		$this->_db->query($sql);
		$row = $this->_db->fetchRow();
		$this->category_name = $row['name'];
		//get Realm
		$this->realm = $realms[$this->realm_id];
		//get Replies
		$replies = array();
		$sql = "SELECT * FROM ticket_replies WHERE ticket_id={$this->id} ORDER BY updated_at";
		$this->_db->query($sql);
		while($reply = $this->_db->fetchRow()){
			$tuser = new User();
			$tuser->loadUser($reply['account_id'],false);
			$reply['user'] = $tuser;
			$this->replies[] = new TicketReply($reply,false);
		}
	}
	
	function getCategory($id){
		
		return $row['name'];
	}
	
	function getCategories(){
		$sql = "SELECT * FROM ticket_categories";
		$this->_db->query($sql);
		$categories = array();
		while($category = $db->fetchRow()){
			$categories[$category['id']] = $category['name'];
		}
		$this->categories = $categories;
		return $this->categories;
	}
}