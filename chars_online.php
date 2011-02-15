<?php
require_once('common.php');

if(isset($_GET['realm'])){
	$realm = $realms[$_GET['realm']]; 
} else {
	$realm = $realms[1]; 
}
if(isset($_GET['order'])){
	$sort_order = protect($_GET['order']);
	if($sort_order == 'ASC'){
		$new_sort_order = 'DESC';
	} else {
		$new_sort_order = 'ASC';
	}
} else {
	$sort_order = 'ASC';
	$new_sort_order = 'ASC';
}

if(isset($_GET['sort'])){
	$chars = $realm->get_online_chars('`'.protect($_GET['sort']).'` '.$sort_order);
} else {
	$chars = $realm->get_online_chars();
}

$chars_count = $realm->get_online_chars_count();
$chars_ally_count = $realm->get_online_ally_chars_count();
$chars_horde_count = $realm->get_online_horde_chars_count();
$gms = $realm->get_online_gm_chars();
$gms_count = count($gms);

$tpl = $twig->loadTemplate('chars_online.tpl');
echo $tpl->render(array(
	'chars' => $chars, 
	'chars_count' => $chars_count, 
	'ally_count' => $chars_ally_count, 
	'horde_count' => $chars_horde_count,
	'gms' => $gms,
	'gms_count' => $gms_count,
	'realm_id' => $realm->id,
	'sort_order' => $new_sort_order
));
?>