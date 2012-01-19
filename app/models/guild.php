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

class Guild extends BaseModel {
    static $dbname = 'realm';
    static $dbid = null;
    static $table = 'guild';
    static $primary_key = 'guildid';
    static $name_field = 'name';
    static $plural = 'guilds';
    static $fields = array(
        'guildid',
        'name',
        'leaderguid',
        'info',
        'motd',
        'createdate',
        'BankMoney'
    );
    
    static $relations = array(
        'leader' => array(
            'model' => 'Character',
            'type' => 'has_one',
            'field' => 'guid',
            'fk' => 'leaderguid',
            'lambda' => array(
                '$find->realm($lambda->realm->id);'
            )
        )
    );
    
    public function scope_realm($find, $realm_id){
        $find->dbid = $realm_id;
        $find->additions(array('realm' => Realm::find($realm_id)));
        return $find;
    }

    public function before_save($sql){
        $sql->dbid = $this->realm->id;
        return true;
    }

    public function get_members(){
        $find = Character::find()
            ->realm($this->realm->id)
            ->join("INNER", 'guild_member' ,array('rank','guildid'),'guid')
            ->where(array('guild_member.guildid' => $this->guildid))
            ->order('guild_member.rank')
            ->limit(500);
        $members = $find->all();
        return $members;
    }

    public function validate(){
        if(preg_match('/^[0-9a-zA-Z ]{1,24}$/',$this->name) != 1){
            $this->errors[] = "Invalid name";
            return false;
        }
        return true;
    }
}
