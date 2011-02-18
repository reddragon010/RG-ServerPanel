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
	return $path . ($char->data['gender']) . "-" . $char->data['race'] . "-" . $char->data['class'] . ".gif";
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

// -- RepoTracker
function format_author($author){
	$email = $author->get_email();
	$a = explode(" ", $email);
	return $a[0];
}

function format_repo($repo){
	$op = explode("-",$repo);
	$op = explode(".",$op[1]);
	return $op[0];
}

function time_ago($time)
{
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago";
}

// -- IniTests
function boss_icon($icon){
	global $config;
	if($icon == NULL){
		$icon = "INV_Misc_QuestionMark.gif";
	}
	echo "<img src=\"themes/{$config['theme']}/images/icons/boss/$icon\" width=\"32\" height=\"32\">";
}

//---------------------------------------------------------------------------
//-- Functions
//---------------------------------------------------------------------------
function progress_bar($id, $val, $max){
	$progress = $val / $max * 100;
	echo '<div class="progressbar">';
	echo '<script>$(function() {$( "#progressbar_' . $id . '" ).progressbar({value: ' . $progress . '});});</script>';
	echo '<div id="progressbar_' . $id . '"></div>';
	echo '</div>';
}

// -- Form 
/**
 *
 * @Create dropdown of years
 * @param int $start_year
 * @param int $end_year
 * @param string $id The name and id of the select object
 * @param int $selected
 * @return string
 *
 */
 function selectYears($start_year, $end_year, $id='year_select', $selected=null)
 {

     /*** the current year ***/
     $selected = is_null($selected) ? date('Y') : $selected;

     /*** range of years ***/
     $r = range($start_year, $end_year);

     /*** create the select ***/
     $select = '<select name="'.$id.'" id="'.$id.'">';
     foreach( $r as $year )
     {
         $select .= "<option value=\"$year\"";
         $select .= ($year==$selected) ? ' selected="selected"' : '';
         $select .= ">$year</option>\n";
     }
     $select .= '</select>';
     return $select;
 }

 /*
 *
 * @Create dropdown list of months
 * @param string $id The name and id of the select object
 * @param int $selected
 * @return string
 *
 */
 function selectMonths($id='month_select', $selected=null)
 {
     /*** array of months ***/
     $months = array(
             1=>'January',
             2=>'February',
             3=>'March',
             4=>'April',
             5=>'May',
             6=>'June',
             7=>'July',
             8=>'August',
             9=>'September',
             10=>'October',
             11=>'November',
             12=>'December');

     /*** current month ***/
     $selected = is_null($selected) ? date('m') : $selected;

     $select = '<select name="'.$id.'" id="'.$id.'">'."\n";
     foreach($months as $key=>$mon)
     {
         $select .= "<option value=\"$key\"";
         $select .= ($key==$selected) ? ' selected="selected"' : '';
         $select .= ">$mon</option>\n";
     }
     $select .= '</select>';
     return $select;
 }

 /**
 *
 * @Create dropdown list of days
 * @param string $id The name and id of the select object
 * @param int $selected
 * @return string
 *
 */
 function selectDays($id='day_select', $selected=null)
 {
     /*** range of days ***/
     $r = range(1, 31);

     /*** current day ***/
     $selected = is_null($selected) ? date('d') : $selected;

     $select = "<select name=\"$id\" id=\"$id\">\n";
     foreach ($r as $day)
     {
         $select .= "<option value=\"$day\"";
         $select .= ($day==$selected) ? ' selected="selected"' : '';
         $select .= ">$day</option>\n";
     }
     $select .= '</select>';
     return $select;
 }

 /**
 *
 * @create dropdown list of hours
 * @param string $id The name and id of the select object
 * @param int $selected
 * @return string
 *
 */
 function selectHours($id='hours_select', $selected=null)
 {
     /*** range of hours ***/
     $r = range(1, 24);

     /*** current hour ***/
     $selected = is_null($selected) ? date('H') : $selected;

     $select = "<select name=\"$id\" id=\"$id\">\n";
     foreach ($r as $hour)
     {
         $select .= "<option value=\"$hour\"";
         $select .= ($hour==$selected) ? ' selected="selected"' : '';
         $select .= ">$hour</option>\n";
     }
     $select .= '</select>';
     return $select;
 }

 /**
 *
 * @create dropdown list of minutes
 * @param string $id The name and id of the select object
 * @param int $selected
 * @return string
 *
 */
 function selectMinutes($id='minute_select', $selected=null)
 {
     /*** array of mins ***/
     $minutes = array(0, 15, 30, 45);

 $selected = in_array($selected, $minutes) ? $selected : 0;

     $select = "<select name=\"$id\" id=\"$id\">\n";
     foreach($minutes as $min)
     {
         $select .= "<option value=\"$min\"";
         $select .= ($min==$selected) ? ' selected="selected"' : '';
         $select .= ">".str_pad($min, 2, '0')."</option>\n";
     }
     $select .= '</select>';
     return $select;
 }

function selectArray($id,$array,$selected=null){
	
	$select = "<select name=\"$id\" id=\"$id\">\n";
   foreach($array as $key => $val)
   {
       $select .= "<option value=\"$key\"";
			$select .= ($key==$selected) ? ' selected="selected"' : '';
       $select .= ">".$val."</option>\n";
   }
   $select .= '</select>';
   return $select;
}
?>