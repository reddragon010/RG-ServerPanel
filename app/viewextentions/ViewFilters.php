<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of RG-ServerPanel.
 *
 *    RG-ServerPanel is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    RG-ServerPanel is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with RG-ServerPanel.  If not, see <http://www.gnu.org/licenses/>.
 */

class ViewFilters extends \Dreamblaze\Framework\Core\ViewExtention {

    public function getFilters(){
        return array_merge(
            $this->generateHtmlFilters(array(
                'money',
                'classicon',
                'raceicon',
                'factionicon',
                'online',
                'locked',
                'banned',
                'account_status',
                'character_status',
                'wowhead_spell',
                'nl2br',
                'banning_gm'
            )),
            $this->generateFilters(array(
                'avatar',
                'mapname',
                'gendername',
                'zonename',
                'uptime',
                'ago',
                'gravatar_url'
            ))
        );
    }

    function money($money) {
        if ($money < 100) {
            $k = $money;
            return "<span class=\"moneycopper\">{$k}</span>";
        } elseif ($money < 10000) {
            $s = intval($money / 100);
            $k = $money - $s * 100;
            return "<span class=\"moneysilver\">{$s}</span><span class=\"moneycopper\">{$k}</span>";
        } else {
            $g = intval($money / 10000);
            $s = intval(($money - $g * 10000) / 100);
            $k = $money - ($g * 10000 + $s * 100);
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

    function classicon($char) {
        global $CLASSES, $l;
        $name = $l['classes'][$CLASSES[$char->class]];
        return "<img class=\"class_icon_small\" src=\"/images/icons/class/{$char->class}.gif\" title=\"{$name}\" />";
    }

    function raceicon($char) {
        global $RACES, $l;
        $name = $l['races'][$RACES[$char->race]];
        return "<img class=\"race_icon_small\" src=\"/images/icons/race/{$char->race}-{$char->gender}.gif\" title=\"{$name}\" />";
    }

    function factionicon($char) {
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
        $mapname = \Dreamblaze\Framework\Core\I18n::get('maps', $char->map);
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

    function online($online) {
        if ($online) {
            return '<img alt="online" src="/images/icons/online.png" />';
        } else {
            return '<img alt="offline" src="/images/icons/offline.png" />';
        }
    }

    //TODO: make soft-delete safe
    function locked($account) {
        if(!is_object($account))
            return '';

        $funcs = new ViewFunctions();
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

    //TODO: make soft-delete safe
    function banned($account){
        if(!is_object($account))
            return '';

        $funcs = new ViewFunctions();
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

    function account_status($account) {
        $op = "";
        $op .= $this->locked($account);
        $op .= $this->online($account->online);
        $op .= $this->banned($account);
        return $op;
    }
    
    function character_status($character){
        $account = $character->accountobj;
        $op = "";
        $op .= $this->locked($account);
        $op .= $this->online($character->online);
        $op .= $this->banned($account);
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
    
    function wowhead_spell($spellid){
        return "<a href=\"http://www.wowhead.com/spell=$spellid\">$spellid</a>";
    }
    
    function nl2br($string){
        return nl2br($string);
    }

    function banning_gm($ban){
        $funcs = new ViewFunctions();
        if(is_a($ban->banning_account, 'Account'))
            return $funcs->link_to_account($ban->banning_account);
        else
            return $ban->bannedby;
    }
}