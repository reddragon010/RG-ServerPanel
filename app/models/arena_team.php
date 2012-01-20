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

class ArenaTeam extends BaseModel {
    static $dbname = 'realm';
    static $dbid = null;
    static $table = 'arena_team';
    static $primary_key = 'arenateamid';
    static $name_field = 'name';
    static $plural = 'arenateams';
    static $fields = array(
        'arenateamid',
        'name',
        'type',
        'rating',
        'seasongames',
        'seasonwins',
        'weekgames',
        'weekwins',
    );
    
    public function scope_realm($find, $realm_id){
        $find->dbid = $realm_id;
        $find->additions(array('realm' => Realm::find($realm_id)));
        return $find;
    }

    public function get_members(){
        $find = Character::find()
            ->realm($this->realm->id)
            ->join("INNER", 'arena_team_member' ,array('weekgames','weekwins','seasongames','seasonwins','personalrating','arenateamid'),'guid')
            ->where(array('arena_team_member.arenateamid' => $this->arenateamid));
        $members = $find->all();
        return $members;
    }
}
