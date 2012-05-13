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

class AccountsController extends ApplicationController {

    var $before = array(
        'check_login'
    );

    function index($params=array()) {
        if(empty($params['order'])){
            $order = 'id';
        } else {
            $order = $params['order'];
        }
        $accounts = Account::find()
                ->where(array_filter($params))
                ->order($order);

        if(isset($params['page'])) $accounts->page($params['page']);

        if(isset($params['type']) && $params['type'] == 'json'){
            $this->render_json($accounts->all());
        } else {
            $this->render(array(
                'accounts' => $accounts->all(),
                'acc_count' => $accounts->count()
            ));
        }
    }

    function show($params) {
        $account = Account::find($params['id']);
        if(!empty($account->username)){
            $this->render(array(
                'account' => $account,
                'same_ip_accounts' => $account->accounts_with_same_ip,
                'partners' => $account->partners
            ));
        } else {
            $this->render_error('404');
        }
    }

    function add() {
        $this->render();
    }

    function create($params) {
        $account = new Account($params, true);

        if($account->save()){
            $account->reload();
            Event::trigger(Event::TYPE_ACCOUNT_ADD, User::$current->account, $account);
            $this->render_ajax('success','Account created');
        } else {
            if(isset($account->errors[0])){
                $this->render_ajax('error', $account->errors[0]);
            } else {
                $this->render_ajax('error', "Can't create Account");
            }
        }
    }

    function edit($params) {
        $account = Account::find($params['id']);
        if($account){
            $this->render(array('account' => $account));
        } else {
            $this->render_ajax('error', 'Account not found!');
        }
    }

    function update($params) {
        $account = Account::find($params['id']);
        if($account){
            $change_texts = array();
            if(isset($params['email']) && $account->email != $params['email']){
                $change_texts[] = "mail";
                $account->email = $params['email'];
            }
            if(!empty($params['password']) && !empty($params['password_confirm'])){
                if($params['password'] == $params['password_confirm']){
                    $change_texts[] = "pw";
                    $account->v = 0;
                    $account->s = 0;
                    $account->sha_pass_hash = Account::hash_password($account->username, $params['password']);
                    $account->password = $params['password'];
                    $account->password_confirm = $params['password_confirm'];
                } else {
                    $this->render_ajax('error', 'Password confirmation failed!');
                    return;
                }
            }
            if($account->save()){
                Event::trigger(Event::TYPE_ACCOUNT_EDIT, User::$current->account, $account, join(', ', $change_texts));
                $this->render_ajax('success','Account updated');
            } else {
                if(isset($account->errors[0])){
                    $this->render_ajax('error', $account->errors[0]);
                } else {
                    $this->render_ajax('error', "Can't save Account");
                }
            }
        } else {
            $this->render_ajax('error', 'Account not found!');
        }
    }
    
    function lock($params){
        $account = Account::find($params['id']);
        if($account){
            if($account->lock()){
                $this->flash('success', 'Account locked');
                Event::trigger(Event::TYPE_ACCOUNT_LOCK, User::$current->account, $account->id);
            } else {
                $this->flash('error', 'Error! ' . $this->errors[0]);
            }
        } else {
            $this->flash('error', 'Account not found!');
        }
        $this->redirect_back();
    }
    
    function unlock($params){
        $account = Account::find($params['id']);
        if($account){
            if($account->unlock()){
                $this->flash('success', 'Account unlocked');
                Event::trigger(Event::TYPE_ACCOUNT_UNLOCK, User::$current->account, $account->id);
            } else {
                $this->flash('error', 'Error! ' . $this->errors[0]);
            }
        } else {
            $this->flash('error', 'Account not found!');
        }
        $this->redirect_back();
    }
}
