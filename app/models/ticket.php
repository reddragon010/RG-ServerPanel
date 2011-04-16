<?php
class Ticket extends Model
{
	var $replies = array();
	var $user = NULL;
	var $realm = NULL;
	var $character = NULL;
	
	static $table = 'ticket_tickets';
	static $dbname = 'web';
	
    public static function before_find(&$options){
        if(empty($options['join']))
            $options['join'] = array('ticket_categories', 'key' => 'category_id');
    }

	function after_build(){
		global $realms;
		//get User
		$this->user = User::find($this->account_id);
		//get Character
        $this->realm = Realm::find($this->realm_id);
		$this->character = $this->realm->find_characters($this->character_id);
		//get Replies
		//$this->replies = Ticketreply::find('all',array('conditions' => array('ticket_id = ?', $this->id), 'order' => 'updated_at'));
	}
	
	function get_category($id){
		return $row['name'];
	}
	
	function get_categories(){
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