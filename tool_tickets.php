<?php
require_once('common.php');
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
?>
