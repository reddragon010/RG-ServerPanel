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
	global $config;
	if($char->data['level'] < 20){
		$path = "themes/{$config['theme']}/images/avatars/def/";
	} elseif($char->data['level'] < 60) {
		$path = "themes/{$config['theme']}/images/avatars/wow/";
	} elseif($char->data['level'] < 70) {
		$path = "themes/{$config['theme']}/images/avatars/60/";
	} elseif($char->data['level'] < 80) {
		$path = "themes/{$config['theme']}/images/avatars/70/";
	} elseif($char->data['level'] == 80) {
		$path = "themes/{$config['theme']}/images/avatars/80/";
	}
	return $path . ($char->data['gender'] - 1) . "-" . $char->data['race'] . "-" . $char->data['class'] . ".gif";
}

function class_icon($char){
	global $CLASSES,$l,$config;
	$name = $l['classes'][$CLASSES[$char->data['class']]];
	return "<img class=\"class_icon_small\" src=\"themes/{$config['theme']}/images/icons/class/{$char->data['class']}.gif\" title=\"{$name}\" />";
}

function race_icon($char){
	global $RACES,$l,$config;
	$name = $l['races'][$RACES[$char->data['race']]];
	return "<img class=\"race_icon_small\" src=\"themes/{$config['theme']}/images/icons/race/{$char->data['race']}-{$char->data['gender']}.gif\" title=\"{$name}\" />";
}

function faction_icon($char){
	global $HORDE, $ALLIANCE, $FACTIONS, $l,$config;
	if($char->gm){
		$faction = $FACTIONS[2];
	} else {
		if(in_array($char->data['race'], $HORDE)){
			$faction = $FACTIONS[1];
		} elseif(in_array($char->data['race'], $ALLIANCE)){
			$faction = $FACTIONS[0];
		}
	}
	$faction_name = $l['factions'][$faction];
	return "<img class=\"race_icon_small\" src=\"themes/{$config['theme']}/images/icons/faction/{$faction}.gif\" title=\"{$faction_name}\" />";
}

function map_name($char){
	global $MAPS, $l;
	$map_name = $l['maps'][$MAPS[$char->data['map']]];
	if($map_name){
		return $map_name;
	} else {
		return $l['maps'][$MAPS[-1]];
	}
}

function gender_name($char){
	global $GENDERS, $l;
	return $l['genders'][$GENDERS[$char->data['gender']]];
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