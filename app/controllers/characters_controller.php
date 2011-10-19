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

class CharactersController extends BaseController {

    var $before = array(
        'check_login'
    );

    function index($params=array()) {
        if (isset($params['account']) && $params['account'] == '') {
            $this->render_error(404);
            return;
        }
        
        $realms = Realm::find()->all();
        
        $realmnames = array('all' => 'All');
        foreach($realms as $r){
            $realmnames[$r->id] = $r->name;
        }
        
        $find = Character::find()->where($params)->page($params['page']);
        $chars = array();
        if(isset($params['realm']) && is_numeric($params['realm'])){
            $find = $find->realm($params['realm']);
            $chars += $find->all();
            $chars_count += $find->count();
        } else {
            $chars_count = 0;
            foreach ($realms as $realm) {
                $find = $find->realm($realm->id);
                $chars += $find->all();
                $chars_count += $find->count();
            }
        }

        $data = array(
            'chars_count' => $chars_count,
            'characters' => $chars,
            'realmnames' => $realmnames
        );

        if (isset($params['partial'])) {
            $this->render_partial('shared/characters', $data);
        } else {
            $this->render($data);
        }
    }

    function show($params) {
        $char = Character::find()->where(array('guid' => $params['id']))->realm($params['rid'])->first();
        if (!empty($char->name)) {
            $this->render(array('character' => $char));
        } else {
            $this->render_error('404');
        }
    }

    function edit($params) {
        $char = Character::find()->where(array('guid' => $params['id']))->realm($params['rid'])->first();
        $this->render(array('character' => $char));
    }

    function update($params) {
        $char = Character::find()->where(array('guid' => $params['guid']))->realm($params['rid'])->first();
        if ($char) {
            if (isset($params['account_name'])) {
                $account = Account::find()->where(array('username' => $params['account_name']))->first();
                if ($account) {
                    $params['account'] = $account->id;
                } else {
                    $this->render_ajax('error', "Can't find Account");
                    return false;
                }
            }
            
            $change_texts = array();
            if(isset($params['account']) && $char->account != $params['account']){
                $change_texts[] = "Old Owner: " . $char->accountobj->username;
                $char->account = $params['account'];
            }
            if(isset($params['name']) && $char->name != $params['name']){
                $change_texts[] = "Old Name: " . $char->name;
                $char->name = $params['name'];
            }

            if ($char->save()) {
                $this->render_ajax('success', 'Character updated');
                Event::trigger(Event::TYPE_CHARACTER_EDIT, User::$current->account, $char, join(', ', $change_texts));
            } else {
                if (isset($char->errors[0])) {
                    $this->render_ajax('error', $char->errors[0]);
                } else {
                    $this->render_ajax('error', "Can't save Character");
                }
            }
        } else {
            $this->render_ajax('error', 'Characters not found!');
        }
    }

    function recover($params) {
        $char = Character::find()->where(array('guid' => $params['id']))->realm($params['rid'])->first();
        if ($char) {
            if ($char->recover()) {
                $this->flash('success', 'Successfully recoverd');
            } else {
                $this->flash('error', $char->errors[0]);
            }
        } else {
            $this->flash('error', 'Char not found');
        }
        $this->redirect_back();
    }

    function move($params) {
        $char = Character::find()->where(array('guid' => $params['id']))->realm($params['rid'])->first();
        $this->render(array('character' => $char));
    }

    function kick($params) {
        $char = Character::find()->where(array('guid' => $params['id']))->realm($params['rid'])->first();
        if ($char->guid == $params['id']) {
            $answer = $char->kick();
            if ($answer == false) {
                $this->flash('error', 'Error on kick: ' . $char->errors[0]);
            } else {
                $this->flash('success', 'Kicked (' . $answer . ')');
            }   
        } else {
            $this->flash('error', "Can't find character");
        }
        $this->redirect_back();
    }

}
