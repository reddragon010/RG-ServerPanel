<?php
/**
* 
*/
class CataTemplateExtention extends TemplateExtention
{
	//---------------------------------------------------------------------------
	//-- Globals
	//---------------------------------------------------------------------------	
	
	function tpl_global_user(){
		global $user;
		return $user;
	}
	
	function tpl_global_realms(){
		global $config;
		$realms = array();
		foreach($config['db']['realm'] as $key => $value){
			$realms[] = Realm::find($key);
		}
		return $realms;
	}
	
	function tpl_global_STATUS(){
		global $STATUS;
		return $STATUS;
	}
	
	function tpl_global_rooturl(){
		global $config;
		return $config['page_root'];
	}
	
	function tpl_global_themeurl(){
		global $config;
		return $config['page_root'] . '/themes/' . $config['theme'];
	}
	
	function tpl_global_TICKETSTATUS(){
		global $TICKET_STATUS;
		return $TICKET_STATUS;
	}
	
	//---------------------------------------------------------------------------
	//-- Filters
	//---------------------------------------------------------------------------

	//-- Chars
	//---------------------------------------
	function tpl_filter_money($money){
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

	function tpl_filter_avatar($char){
		global $config;
		$base = "{$config['page_root']}/themes/{$config['theme']}/images/avatars/";
		if(is_object($char)){
			if($char->data['level'] < 20){
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

	function tpl_filter_classicon_html($char){
		global $CLASSES,$l,$config;
		$name = $l['classes'][$CLASSES[$char->class]];
		return "<img class=\"class_icon_small\" src=\"{$config['page_root']}/themes/{$config['theme']}/images/icons/class/{$char->class}.gif\" title=\"{$name}\" />";
	}

	function tpl_filter_raceicon_html($char){
		global $RACES,$l,$config;
		$name = $l['races'][$RACES[$char->race]];
		return "<img class=\"race_icon_small\" src=\"{$config['page_root']}/themes/{$config['theme']}/images/icons/race/{$char->race}-{$char->gender}.gif\" title=\"{$name}\" />";
	}

	function tpl_filter_factionicon_html($char){
		global $HORDE, $ALLIANCE, $FACTIONS, $l,$config;
		if(in_array($char->race, $HORDE)){
			$faction = $FACTIONS[1];
		} elseif(in_array($char->race, $ALLIANCE)){
			$faction = $FACTIONS[0];
		}
		$faction_name = $l['factions'][$faction];
		return "<img class=\"race_icon_small\" src=\"{$config['page_root']}/themes/{$config['theme']}/images/icons/faction/{$faction}.gif\" title=\"{$faction_name}\" />";
	}

	function tpl_filter_mapname($char){
		global $MAPS, $l;
		if(isset($MAPS[$char->map])){
			return $l['maps'][$MAPS[$char->map]];
		} else {
			return $l['maps'][$MAPS[-1]];
		}
	}

	function tpl_filter_gendername($char){
		global $GENDERS, $l;
		return $l['genders'][$GENDERS[$char->gender]];
	}

	function tpl_filter_zonename($char){
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
	function tpl_filter_uptime($uptime){
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

	function tpl_filter_online($online){
		if($online){
			return '<span class="realm_online">ONLINE</span>';
		} else {
			return '<span class="realm_offline">OFFLINE</span>';
		}
	}

	// -- RepoTracker
	function tpl_filter_author($author){
		$email = $author->get_email();
		$a = explode(" ", $email);
		return $a[0];
	}

	function tpl_filter_repo($repo){
		$op = explode("-",$repo);
		$op = explode(".",$op[1]);
		return $op[0];
	}

	function tpl_filter_timeago($time)
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
	function tpl_filter_bossicon($icon){
		global $config;
		if($icon == NULL){
			$icon = "INV_Misc_QuestionMark.gif";
		}
		echo "<img src=\"{$config['page_root']}/themes/{$config['theme']}/images/icons/boss/$icon\" width=\"32\" height=\"32\">";
	}
	
	function tpl_filter_substr($string, $start, $length=null){
		if(empty($length)){
			return substr($string, $start);
		} else {
			return substr($string, $start, $length);
		}
	}

	//---------------------------------------------------------------------------
	//-- Functions
	//---------------------------------------------------------------------------
	function tpl_function_progressbar($id, $val, $max){
		$progress = $val / $max * 100;
		echo '<div class="progressbar">';
		echo '<script>$(function() {$( "#progressbar_' . $id . '" ).progressbar({value: ' . $progress . '});});</script>';
		echo '<div id="progressbar_' . $id . '"></div>';
		echo '</div>';
	}

	function tpl_function_flushflash(){
		if(isset($_SESSION['flash'])) {
			if($_SESSION['flash']['hops'] <= 0){
				$flash = $_SESSION['flash'];
				$_SESSION['flash'] = null;
				return $flash;
			} else {
				$_SESSION['flash']['hops'] = $_SESSION['flash']['hops']-1;
			}
		}
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
	 function tpl_function_selectYears($start_year, $end_year, $id='year_select', $selected=null)
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
	 function tpl_function_selectMonths($id='month_select', $selected=null)
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
	 function tpl_function_selectDays($id='day_select', $selected=null)
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
	 function tpl_function_selectHours($id='hours_select', $selected=null)
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
	 function tpl_function_selectMinutes($id='minute_select', $selected=null)
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

	function tpl_function_selectArray($id,$array,$selected=null){

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
}
