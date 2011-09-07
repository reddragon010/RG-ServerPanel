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
        $mapname = i18n::get('maps', $char->map);
        if (!is_string($mapname)) {
            return $char->map;
        } else {
            return $mapname;
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
            return '<img alt="online" src="/images/icons/online.png" />';
        } else {
            return '<img alt="offline" src="/images/icons/offline.png" />';
        }
    }

    function locked_html($account) {
        $funcs = new tplfunctions();
        if ($account->locked) {
            $link = $funcs->link_to('accounts', 'unlock', array('id' => $account->id));
            $op = "<a class=\"confirm\" title=\"Unlock Account {$account->id}\" href=\"$link\">";
            $op .= '<img alt="locked" src="/images/icons/locked.jpeg" />';
            $op .= '</a>';
        } else {
            $link = $funcs->link_to('accounts', 'lock', array('id' => $account->id));
            $op = "<a class=\"confirm\" title=\"Lock Account {$account->id}\" href=\"$link\">";
            $op .= '<img alt="unlocked" src="/images/icons/unlocked.jpeg" />';
            $op .= '</a>';
        }
        return $op;
    }
    
    function banned_html($account){
        $funcs = new tplfunctions();
        if($account->banned){
            $link = $funcs->link_to('account_banns', 'delete', array('id' => $account->id));
            $op = "<a class=\"confirm\" title=\"Unban Account {$account->id}\" href=\"$link\">";
            $op .= '<img alt="banned" src="/images/icons/banned.jpeg" />';
            $op .= '</a>';
        } else {
            $link = $funcs->link_to('account_banns', 'add', array('account_id' => $account->id));
            $op = "<a class=\"remote_form\" width=500 title=\"Ban Account {$account->id}\" href=\"$link\">";
            $op .= '<img alt="unbanned" src="/images/icons/unbanned.jpeg" />';
            $op .= '</a>';
        }
        return $op;
    }

    function account_status_html($account) {
        $filters = new tplfilters();
        $op = "";
        $op .= $filters->locked_html($account);
        $op .= $filters->online_html($account->online);
        $op .= $filters->banned_html($account);
        return $op;
    }
    
    function character_status_html($character){
        $filters = new tplfilters();
        $account = $character->accountobj;
        $op = "";
        $op .= $filters->locked_html($account);
        $op .= $filters->online_html($character->online);
        $op .= $filters->banned_html($account);
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
    
    function wowhead_spell_html($spellid){
        return "<a href=\"http://www.wowhead.com/spell=$spellid\">$spellid</a>";
    }
    
    function nl2br_html($string){
        return nl2br($string);
    }
}