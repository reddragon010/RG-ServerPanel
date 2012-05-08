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

class CheatLogEntry extends ApplicationModel {
    static $dbname = 'realm';
    static $dbid = null;
    static $table = 'anticheat_log';
    static $fields = array('guid', 'checktype', 'map', 'zone', 'alarm_time', 'charname', 'lastspell');
    static $relations = array(
        'config' => array(
            'model' => 'CheatConfigEntry',
            'type' => 'has_one',
            'field' => 'checktype',
            'fk' => 'checktype',
            'lambda' => array(
                'return $find->realm($lambda->realm->id);'
            )
        ),
        'character' => array(
            'model' => 'Character',
            'type' => 'has_one',
            'field' => 'guid',
            'fk' => 'guid',
            'lambda' => array(
                '$find->realm($lambda->realm->id);'
            )
        )
    );
    public $realm;
    
    public function scope_realm($find, $realm_id){
        $find->dbid = $realm_id;
        $find->additions(array('realm' => Realm::find($realm_id)));
        return $find;
    }

}
