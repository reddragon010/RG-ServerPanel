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

class GuildsController extends ApplicationController {
    var $before = array(
        'check_login'
    );
    
    function index($params=array()) {
        $realms = Realm::find()->available()->all();
        $realmnames = array('all' => 'All');
        foreach($realms as $r){
            $realmnames[$r->id] = $r->name;
        }
        
        $find = Guild::find()->where(array_filter($params));

        if(isset($params['page'])) $find->page($params['page']);

        $guilds = array();
        $guilds_count = 0;
        if(isset($params['realm']) && is_numeric($params['realm'])){
            $find->realm($params['realm']);
            $guilds += $find->all();
            $guilds_count += $find->count();
        } else {
            $chars_count = 0;
            foreach ($realms as $realm) {
                $find = $find->realm($realm->id);
                $guilds += $find->all();
                $guilds_count += $find->count();
            }
        }
        
        $this->render(array(
            'guilds' => $guilds,
            'guilds_count' => $guilds_count,
            'realmnames' => $realmnames
        ));
    }
    
    function show($params){
        $guild = Guild::find()->where(array('guildid'=> $params['id']))->realm($params['rid'])->first();

        $find = Character::find()
            ->realm($guild->realm->id)
            ->join("INNER", 'guild_member' ,array('rank','guildid'),'guid')
            ->where(array('guild_member.guildid' => $guild->guildid))
            ->order('guild_member.rank');

        $data = array(
            'guild' => $guild,
            'members_count' => $find->count()
        );
        $this->render($data);
    }

    function edit($params){
        $guild = Guild::find()->where(array('guildid'=> $params['guildid']))->realm($params['rid'])->first();
        if($guild && $guild->guildid == $params['guildid']){
            $this->render(array('guild' => $guild));
        }
    }

    function update($params){
        $guild = Guild::find()->where(array('guildid'=> $params['guildid']))->realm($params['rid'])->first();
        if($guild && $guild->guildid == $params['guildid']){
            if(!empty($params['name']))
                $guild_oldname = $guild->name;
                $guild->name = $params['name'];
            if($guild->save()){
                Event::trigger(Event::TYPE_GUILD_EDIT, User::$current->account, $guild, $guild_oldname);
                $this->render_ajax('success','Guild successfully updated');
            } else {
                $this->render_ajax('error', $guild->errors[0]);
            }
        }
    }

    function members($params){
        $guild = Guild::find()->where(array('guildid' => $params['id']))->realm($params['rid'])->first();

        $find = Character::find()
            ->realm($guild->realm->id)
            ->join("INNER", 'guild_member' ,array('rank','guildid'),'guid')
            ->where(array('guild_member.guildid' => $guild->guildid))
            ->order('guild_member.rank');

        if(isset($params['page'])) $find->page($params['page']);
        $members = $find->all();
        $members_count = $find->count();

        $data = array(
            'members' => $members,
            'members_count' => $members_count
        );
        $this->render_partial("members",$data);
    }
}