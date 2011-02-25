<?php
/**
* 
*/
class TicketReply extends Model
{
	const table = 'ticket_replies';
	const dbname = 'web';
	
	function after_create(){
		$sql = "UPDATE ticket_tickets SET status='$status' WHERE id='{$this->ticket_id}'";
		$this->_db->query($sql);
	}
}
