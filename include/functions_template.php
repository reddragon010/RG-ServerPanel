<?php
//---------------------------------------------------------------------------
//-- Filters
//---------------------------------------------------------------------------

//-- Chars
//---------------------------------------
function money($money){
	if($money < 100){
		$g = 0;
		$s = 0;
		$k = $money;
	} elseif($money < 1000) {
		$g = 0;
		$s = intval($money/100);
		$k = $money - $s*100;
	} else {
		$g = intval($money/1000);
		$s = intval(($money - $g*1000)/100);
		$k = $money - ($g*1000+$s*100);
	}
	return "{$g}g {$s}s {$k}k";
}

function avatar($char){
	if($char->data['level'] < 20){
		$path = "images/avatars/def/";
	} elseif($char->data['level'] < 60) {
		$path = "images/avatars/wow/";
	} elseif($char->data['level'] < 70) {
		$path = "images/avatars/60/";
	} elseif($char->data['level'] < 80) {
		$path = "images/avatars/70/";
	} elseif($char->data['level'] == 80) {
		$path = "images/avatars/80/";
	}
	return $path . $char->data['gender'] . "-" . $char->data['race'] . "-" . $char->data['class'] . ".gif";
}

function class_icon($char){
	global $CLASSES;
	$name = $CLASSES[$char->data['class']];
	return "<img class=\"class_icon_small\" src=\"images/icons/class/{$char->data['class']}.gif\" title=\"{$name}\" />";
}

function race_icon($char){
	global $RACES;
	$name = $RACES[$char->data['race']];
	return "<img class=\"race_icon_small\" src=\"images/icons/race/{$char->data['race']}-{$char->data['gender']}.gif\" title=\"{$name}\" />";
}

function map_name($char){
	global $MAPS;
	return $MAPS[$char->data['map']];
}

function gender_name($char){
	global $GENDERS;
	return $GENDERS[$char->data['gender']];
}

function zone_name($char){
	global $db_web;
	
	$sql = "SELECT `name` FROM `zone` WHERE `id`='{$char->data['map']}';";
	$db_web->query($sql);
	if($db_web->count() > 0){
		$row = $db_web->fetchRow();
		return $row['name'];
	} else {
		return 'Unbekannte Zone';
	}
}

// -- Realm
function uptime($uptime){
	if ($uptime > 86400) { 
    $uptime =  round(($uptime / 24 / 60 / 60),2)." Days";
	}
	elseif($uptime > 3600) { 
    $uptime =  round(($uptime / 60 / 60),2)." Hours";
	}
	else { 
    $uptime =  round(($uptime / 60),2)." Min";
	}
	return $uptime;
}

function online($online){
	if($online){
		return '<span class="realm_online">ONLINE</span>';
	} else {
		return '<span class="realm_offline">OFFLINE</span>';
	}
}
?>