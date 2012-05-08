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

class AccountBannsController extends ApplicationController {
    function index($params){
        /*if(isset($params['id']) && $params['id'] == ''){
            $this->render_error(404);
            return;
        }*/
        $bans = AccountBan::find()->where($params)->order('bandate DESC');

        if(isset($params['page'])) $bans->page($params['page']);

        if(empty($params['render_type']))
            $params['render_type'] = 'html';

        if(isset($params['filter_time']) && $params['filter_time'] == '1'){
            $from = mktime(
                $params['from_hours_select'],
                $params['from_minute_select'],
                0,
                $params['from_month_select'],
                $params['from_day_select'],
                $params['from_year_select']
            );
            $to = mktime(
                $params['to_hours_select'],
                $params['to_minute_select'],
                0,
                $params['to_month_select'],
                $params['to_day_select'],
                $params['to_year_select']
            );

            $bans->where(array("bandate >= :from AND bandate <= :to", 'from' => $from, 'to' => $to));
        }

        $data = array(
            'bans' => $bans->all(),
            'bans_count' => $bans->count(),
        );
        
        if(isset($params['partial'])){
            $this->render_partial('shared/bans', $data);
        } else {
            $this->render($data, $params['render_type']);
        }
    }
    
    function index_partial($params){
        $bans = AccountBan::find()->where($params)->order('bandate DESC');
        $this->render(array(
            'bans_count' => $bans->count(),
            'bans' => $bans->all(),
        ));
    }
    
    function add($params){
        $this->render(array('account_id' => $params['account_id']));
    }
    
    function create($params){
        $time = time();
        switch($params['bantype']){
            case 'perm':
                $params['unbandate'] = $time;
                break;
            case 'time':
                $params['unbandate'] = $time + (86400 * $params['bandays']);
                break;
            case 'detailedtime':
                $params['unbandate'] = mktime(
                        $params['hours_select'], 
                        $parame['mins_select'], 
                        0, 
                        $params['month_select'], 
                        $params['day_select'], 
                        $params['year_select']
                        );
                break;
            case 'save':
                $params['unbandate'] = $time;
                $params['banreason'] = 'Save-Ban';
                break;
        }
        $params['bandate'] = $time;
        $params['active'] = 1;
        $params['bannedby'] = User::$current->id;
        if(AccountBan::create($params, &$obj)){
            Event::trigger(Event::TYPE_ACCOUNT_BAN, User::$current->account, $obj->account);
            $this->render_ajax('success', 'Successfully banned');
        } else {
            $this->render_ajax('error', 'Error! ' . $obj->errors[0]);
        }
    }
    
    function delete($params){
        if(isset($params['id']) && !empty($params['id'])){
            $ban = AccountBan::find()->where(array('id' => $params['id'], 'active' => '1'))->first();
            if($ban){
                $ban->active = 0;
                if($ban->save()){
                   $this->flash('success', 'Successfully unbanned');
                   Event::trigger(Event::TYPE_ACCOUNT_UNBAN, User::$current->account, $ban->account);
                } else {
                   $this->flash('error', 'Error! ' . $ban->errors[0]);
                }
            } else {
                $this->flash('error', 'No Ban found!');
            }
        } else {
            $this->flash('error', 'No ID!');
        }
        $this->redirect_back();
    }
}
