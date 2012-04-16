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

class ArenaTeamsController extends BaseController {
    var $before = array(
        'check_login'
    );
    
    function index($params=array()) {
        $realms = Realm::find()->all();
        $realmnames = array('all' => 'All');
        foreach($realms as $r){
            $realmnames[$r->id] = $r->name;
        }
        
        $find = ArenaTeam::find()->where(array_filter($params))->order('rating DESC');
        if(isset($params['page'])) $find->page($params['page']);
        
        $teams = array();
        $teams_count = 0;
        if(isset($params['realm']) && is_numeric($params['realm'])){
            $find = $find->realm($params['realm']);
            $teams += $find->all();
            $teams_count += $find->count();
        } else {
            $teams_count = 0;
            foreach ($realms as $realm) {
                $find = $find->realm($realm->id);
                $teams += array_merge($teams, $find->all());
                $teams_count += $find->count();
            }
        }
        
        $this->render(array(
            'teams' => $teams,
            'teams_count' => $teams_count,
            'realmnames' => $realmnames
        ));
    }
    
    function show($params){
        $team = ArenaTeam::find()->where(array('arenateamid'=> $params['id']))->realm($params['rid'])->first();
        $data = array(
            'team' => $team
        );
        $this->render($data);
    }
}