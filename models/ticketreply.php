<?php
/**
* 
*/
class TicketReply extends Model
{
	function add($content, $user, $status){
		$sql = "INSERT INTO ticket_replies(content, account_id, ticket_id) VALUES ('$content','{$user->userid}','{$this->id}')";
		$this->db->query($sql);
		$sql = "UPDATE ticket_tickets SET status='$status' WHERE id='{$this->id}'";
		$this->db->query($sql);
		return true; 
	}
	
	function update($id, $content, $user){
		$sql = "UPDATE ticket_replies SET content='$content', account_id='{$user->id}'";
		return $this->db->query($sql);
	}
}
