<?php
require_once('common.php');

$chars = Character::get_online_chars();
$chars_count = Character::get_online_chars_count();
$chars_ally_count = Character::get_online_chars_count(array('`race` IN ('. implode(',' , $ALLIANCE) .')'));
$chars_horde_count = Character::get_online_chars_count(array('`race` IN ('. implode(',', $HORDE) .')'));
$gms = Character::get_online_gm_chars();
$gms_count = count($gms);

$tpl = $twig->loadTemplate('chars_online.tpl');
echo $tpl->render(array(
	'chars' => $chars, 
	'chars_count' => $chars_count, 
	'ally_count' => $chars_ally_count, 
	'horde_count' => $chars_horde_count,
	'gms' => $gms,
	'gms_count' => $gms_count
));
?>