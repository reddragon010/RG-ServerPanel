<?php

class tplfilters {

    function money_html($money) {
        if ($money < 100) {
            $k = $money;
            return "<span class=\"moneycopper\">{$k}</span>";
        } elseif ($money < 1000) {
            $s = intval($money / 100);
            $k = $money - $s * 100;
            return "<span class=\"moneysilver\">{$s}</span><span class=\"moneycopper\">{$k}</span>";
        } else {
            $g = intval($money / 1000);
            $s = intval(($money - $g * 1000) / 100);
            $k = $money - ($g * 1000 + $s * 100);
            return "<span class=\"moneygold\">{$g}</span><span class=\"moneysilver\">{$s}</span><span class=\"moneycopper\">{$k}</span>";
        }
        return false;
    }

    function avatar($char) {
        $theme_url = Environment::$app_theme_url;
        $base = "/images/avatars/";
        if (is_object($char)) {
            if ($char->level < 20) {
                $path = "low/";
            } elseif ($char->level < 60) {
                $path = "wow/";
            } elseif ($char->level < 70) {
                $path = "60/";
            } elseif ($char->level < 80) {
                $path = "70/";
            } elseif ($char->level == 80) {
                $path = "80/";
            }
            return $base . $path . ($char->gender) . "-" . $char->race . "-" . $char->class . ".gif";
        } else {
            return $base . 'low/--.gif';
        }
    }

    function classicon_html($char) {
        global $CLASSES, $l;
        $name = $l['classes'][$CLASSES[$char->class]];
        return "<img class=\"class_icon_small\" src=\"/images/icons/class/{$char->class}.gif\" title=\"{$name}\" />";
    }

    function raceicon_html($char) {
        global $RACES, $l;
        $name = $l['races'][$RACES[$char->race]];
        return "<img class=\"race_icon_small\" src=\"/images/icons/race/{$char->race}-{$char->gender}.gif\" title=\"{$name}\" />";
    }

    function factionicon_html($char) {
        global $HORDE, $ALLIANCE, $FACTIONS, $l;
        if (in_array($char->race, $HORDE)) {
            $faction = $FACTIONS[1];
        } elseif (in_array($char->race, $ALLIANCE)) {
            $faction = $FACTIONS[0];
        }
        $faction_name = $l['factions'][$faction];
        return "<img class=\"race_icon_small\" src=\"/images/icons/faction/{$faction}.gif\" title=\"{$faction_name}\" />";
    }

    function mapname($char) {
        global $MAPS, $l;
        if (isset($MAPS[$char->map])) {
            return $l['maps'][$MAPS[$char->map]];
        } else {
            return $l['maps'][$MAPS[-1]];
        }
    }

    function gendername($char) {
        global $GENDERS, $l;
        return $l['genders'][$GENDERS[$char->gender]];
    }

    function zonename($char) {
        if (isset($char->zone)) {
            $zone = null;
            $zone = Zone::find(intval($char->zone));
            if (is_object($zone)) {
                return $zone->name;
            } else {
                return $char->zone;
            }
        } else {
            return 'Unknown';
        }
    }

    function uptime($uptime) {
        if ($uptime > 86400) {
            $uptime = round(($uptime / 24 / 60 / 60), 2) . " Days";
        } elseif ($uptime > 3600) {
            $uptime = round(($uptime / 60 / 60), 2) . " Hours";
        } else {
            $uptime = round(($uptime / 60), 2) . " Min";
        }
        return $uptime;
    }

    function online_html($online) {
        if ($online) {
            return '<img src="/images/icons/online.gif" />';
        } else {
            return '<img src="/images/icons/offline.gif" />';
        }
    }

    function locked_html($locked) {
        if ($locked) {
            return '<img src="/images/icons/locked.gif" />';
        } else {
            return '<img src="/images/icons/unlocked.gif" />';
        }
    }

    function account_status_html($account) {
        $op = "";
        if($account->locked){
            $op .= '<img src="/images/icons/locked.gif" />';
        } else {
            $op .= '<img src="/images/icons/unlocked.gif" />';
        }
        if($account->online){
            $op .= '<img src="/images/icons/online.gif" />';
        } else {
            $op .= '<img src="/images/icons/offline.gif" />';
        }
        if($account->banned){
            $op .= '<img src="/images/icons/banned.gif" />';
        } else {
            $op .= '<img src="/images/icons/unbanned.gif" />';
        }
        return $op;
    }
    
    function ago($date){
        $periods    = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
        $lengths    = array('60', '60', '24', '7', '4.35', '12', '10');
        $now        = time();
        
        if(empty($date)){
            return 'No Date Provided';
        }
        
        //check if date is a unix-timestamp
        if(((string) (int) $date === $date) && ($date <= PHP_INT_MAX) && ($date >= ~PHP_INT_MAX)){
            $unix_date = $date;
        } else {
            $unix_date  = strtotime($date);
        }
        
        if(empty($unix_date)){
            return 'Bad Date';
        }
        
        if($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = 'ago';
        } else {
            $difference = $unix_date - $now;
            $tense = 'from now';
        }
        
        for($j=0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++){
            $difference /= $lengths[$j];
        }
        
        $difference = round($difference);
        
        if($difference != 1){
            $periods[$j] .= 's';
        }
        
        return "$difference {$periods[$j]} $tense";
    }
    
    function gravatar_url($account){
        $email = $account->email;
        $hash = md5($email);
        return "http://gravatar.com/avatar/$hash?d=retro&s=64";
    }
    
}