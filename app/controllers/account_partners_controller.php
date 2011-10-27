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

class AccountPartnersController extends BaseController {
    function add($params) {
        $this->render(array('account_id' => $params['account_id']));
    }
    
    function create($params){
        if(isset($params['partner']) && !empty($params['partner'])){
            $partner_name_or_id = $params['partner'];
            if(is_numeric($partner_name_or_id)){
                $partner = Account::find($partner_name_or_id);
            } else {
                $partner = Account::find()->where(array('username' => $partner_name_or_id))->first();
            }
            
            if(isset($params['temp'])){
                $params['until'] = mktime(
                        $params['hours_select'], 
                        $parame['mins_select'], 
                        0, 
                        $params['month_select'], 
                        $params['day_select'], 
                        $params['year_select']
                        );
            }
            
            if($partner){
                $params['partner_id'] = $partner->id;
                if(AccountPartner::create($params, &$accpartner)){
                    $this->render_ajax('success', 'Partner created');
                    Event::trigger(Event::TYPE_ACCOUNT_PARTNER_ADD, User::$current, $partner);
                } else {
                    $this->render_ajax('error', 'Creation failed with ' . $accpartner->errors[0]);
                }
            } else {
                $this->render_ajax('error', 'Partner-Account not found!');
            }
        } else {
            $this->render_ajax('error', 'No Partner-ID or -Name!');
        }
    }
    
    function delete($params){
        if(isset($params['id']) && !empty($params['id'])){
            $id = $params['id'];
            $partner = AccountPartner::find($id);
            $partner_account = $partner->partner;
            if($partner){
                if($partner->destroy()){
                    $this->flash('success', 'Partner deleted');
                    Event::trigger(Event::TYPE_ACCOUNT_PARTNER_REMOVE, User::$current, $partner->account, $partner->partner->username);
                } else {
                    $this->flash('error', 'Deletion failed');
                }
            } else {
                $this->flash('error', 'Partner-Account not found!');
            }
        } else {
            $this->flash('error', 'No ID!');
        }
        $this->redirect_back();
    }
}
