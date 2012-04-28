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

class CheatLogController extends ApplicationController {
    function index($params){     
        if(empty($params['realm_id']))
            $params['realm_id'] = Realm::find()->first()->id;

        if(!isset($params['order'])){
            $params['order'] = 'alarm_time DESC';
        }
        
        if(isset($params['checktype']) && $params['checktype'] == 'all')
            unset($params['checktype']);
        
        $realms = Realm::find()->available()->all();
        $realmnames = array();
        foreach($realms as $r){
            $realmnames[$r->id] = $r->name;
        }
        
        $cheatconfig = CheatConfigEntry::find()->realm($params['realm_id'])->all();
        
        $reasons = array('' => 'all');
        foreach($cheatconfig as $cc){
            $reasons[(string)$cc->checktype] = $cc->description;
        }
        
        $log_entries = CheatLogEntry::find()
                ->where($params)
                ->realm($params['realm_id'])
                ->order($params['order']);

        if(isset($params['page'])) $log_entries->page($params['page']);
        
        $data = array(
            'log_entries' => $log_entries->all(),
            'log_entries_count' => $log_entries->count(),
            'realmnames' => $realmnames,
            'realmid' => $params['realm_id'],
            'reasons' => $reasons
        );
        
        if(!isset($params['partial'])){
            $this->render($data);
        } else {
            $this->render_partial('cheatlog', $data);
        }
    }
}
