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

class AccountAclController extends BaseController {
    function index(){
        $realms = Realm::find()->all();
        
        $acls = array();
        $acls[0] = AccountAccess::find()->where(array('realmid' => '-1'))->order('gmlevel DESC')->all();
        foreach($realms as $realm){
            $acls[] = $realm->acl;
        }
        $acls = array_filter($acls);
        $data = array('acls' => $acls);
        $this->render($data);
    }
    
    function edit($params){
        $realms = Realm::find()->all();
        $realm_names = array();
        $realm_names['-1'] = 'Global';
        foreach($realms as $realm){
            $realm_names[$realm->id] = $realm->name;
        }
        $this->render(array(
            'account_access' => AccountAccess::find()->where($params)->first(),
            'realm_names' => $realm_names,
            'id' => $params['id'],
            'realmid' => $params['realmid']
        ));
    }
    
    function update($params) {
        $account_access = AccountAccess::find()->where($params)->first();
        if (!empty($account_access)) {
            if (User::$current->account->highest_gm_level > $account_access->account->highest_gm_level) {

                if (is_numeric($params['account'])) {
                    $new_account = Account::find($params['new_account'])->first();
                } else {
                    $new_account = Account::find()->where(array('username' => $params['new_account']))->first();
                }

                if ($new_account) {
                    if ($account_access->id != $new_account->id)
                        $account_access->id = $enw_account->id;

                    if ($account_access->realmid != $params['new_realmid'])
                        $account_access->realmid = $params['new_realmid'];

                    if ($account_access->gmlevel != $params['new_gmlevel'])
                        $account_access->gmlevel = $params['new_gmlevel'];

                    if ($account_access->save()) {
                        $this->render_ajax('success', 'Successfully Saved');
                    } else {
                        $this->render_ajax('error', 'Can\'t save AccountAccess! ' . $account_access->errors[0]);
                    }
                } else {
                    $this->render_ajax('error', 'Account not found!');
                }
            } else {
                $this->render_ajax('error', 'Your GM-Level have to be highter then the target account\'s level');
            }
        } else {
            $this->render_ajax('error', 'AccountAccess not found!');
        }
    }
    
    function add(){
        $realms = Realm::find()->all();
        $realm_names = array();
        $realm_names['-1'] = 'Global';
        foreach($realms as $realm){
            $realm_names[$realm->id] = $realm->name;
        }
        $this->render(array(
            'realm_names' => $realm_names
        ));
    }
    
    function create($params) {
        if (User::$current->account->highest_gm_level > $params['gmlevel']) {
            if (is_numeric($params['account'])) {
                $account = Account::find($params['account'])->first();
            } else {
                $account = Account::find()->where(array('username' => $params['account']))->first();
            }
            if (!empty($account)) {
                $params['id'] = $account->id;
                if(AccountAccess::create($params,&$account_access)){
                    $this->render_ajax('success', 'Successfully created');
                } else {
                    $this->render_ajax('error', 'Can\'t create Access-Permission ' . $account_access->errors[0]);
                }
            } else {
                $this->render_ajax('error', 'Account not found!');
            }
        } else {
            $this->render_ajax('error', 'You can only add access-permissions with a gmlevel lower then yours');
        }
    }
    
    function delete($params){
        $account_access = AccountAccess::find()->where($params)->first();
        if (!empty($account_access)) {
            if (User::$current->account->highest_gm_level > $account_access->account->highest_gm_level) {
                if($account_access->destroy()){
                    $this->flash('success', 'Deleted');
                } else {
                    $this->flash('error', 'Can\'t delete!');
                }
            } else {
                $this->flash('error', 'Your GM-Level have to be highter then the target account\'s level');
            }
        } else {
            $this->flash('error', 'AccountAccess not found!');
        }
        $this->redirect_back();
    }
}
