<?php
class default_filters {
	//-- Chars
	function money_html($money){
		if($money < 100){
			$k = $money;
			return "<span class=\"moneycopper\">{$k}</span>";
		} elseif($money < 1000) {
			$s = intval($money/100);
			$k = $money - $s*100;
			return "<span class=\"moneysilver\">{$s}</span><span class=\"moneycopper\">{$k}</span>";
		} else {
			$g = intval($money/1000);
			$s = intval(($money - $g*1000)/100);
			$k = $money - ($g*1000+$s*100);
			return "<span class=\"moneygold\">{$g}</span><span class=\"moneysilver\">{$s}</span><span class=\"moneycopper\">{$k}</span>";
		}
		return false;
	}

	function avatar($char){
		global $config;
		$base = "{$config['page_root']}/themes/{$config['theme']}/images/avatars/";
		if(is_object($char)){
			if($char->level < 20){
				$path = "low/";
			} elseif($char->level < 60) {
				$path = "wow/";
			} elseif($char->level < 70) {
				$path = "60/";     
			} elseif($char->level < 80) {
				$path = "70/";     
			} elseif($char->level == 80) {
				$path = "80/";
			}
			return $base . $path . ($char->gender) . "-" . $char->race . "-" . $char->class . ".gif";
		} else {
			return $base . 'low/--.gif';
		}
	}

	function classicon_html($char){
		global $CLASSES,$l,$config;
		$name = $l['classes'][$CLASSES[$char->class]];
		return "<img class=\"class_icon_small\" src=\"{$config['page_root']}/themes/{$config['theme']}/images/icons/class/{$char->class}.gif\" title=\"{$name}\" />";
	}

	function raceicon_html($char){
		global $RACES,$l,$config;
		$name = $l['races'][$RACES[$char->race]];
		return "<img class=\"race_icon_small\" src=\"{$config['page_root']}/themes/{$config['theme']}/images/icons/race/{$char->race}-{$char->gender}.gif\" title=\"{$name}\" />";
	}

	function factionicon_html($char){
		global $HORDE, $ALLIANCE, $FACTIONS, $l,$config;
		if($char->user->is_gm()){
	          $faction = $FACTIONS[2];
	      } elseif(in_array($char->race, $HORDE)){
			$faction = $FACTIONS[1];
		} elseif(in_array($char->race, $ALLIANCE)){
			$faction = $FACTIONS[0];
		}
		$faction_name = $l['factions'][$faction];
		return "<img class=\"race_icon_small\" src=\"{$config['page_root']}/themes/{$config['theme']}/images/icons/faction/{$faction}.gif\" title=\"{$faction_name}\" />";
	}

	function mapname($char){
		global $MAPS, $l;
		if(isset($MAPS[$char->map])){
			return $l['maps'][$MAPS[$char->map]];
		} else {
			return $l['maps'][$MAPS[-1]];
		}
	}

	function gendername($char){
		global $GENDERS, $l;
		return $l['genders'][$GENDERS[$char->gender]];
	}

	function zonename($char){
	      if(isset($char->zone)){
	          $zone = null;
		    $zone = Zone::find(intval($char->zone));
	          if(is_object($zone)){
	              return $zone->name;
	          } else {
	              return $char->zone;
	          }
		} else {
			return 'Unknown';
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

	function online_html($online){
		if($online){
			return '<span class="realm_online">ONLINE</span>';
		} else {
			return '<span class="realm_offline">OFFLINE</span>';
		}
	}
	
	function locked_html($locked){
		if($locked){
			return '<span class="realm_online">LOCKED</span>';
		} else {
			return '<span class="realm_offline"></span>';
		}
	}
	
	function user_status($user){
		return "{$user->locked} | {$user->online}";
	}
}