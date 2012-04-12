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

        $find = Character::find();

        //TODO: hacky hacky hacky
        if($params['name'] != null){
            $char_name = $params['name'];
            $params['name'] = "";
            $find->where(array("(name LIKE :name OR deleteInfos_Name LIKE :name)", 'name' => $char_name));
        }



        $find->where($params);
        $find_count = Character::find()->where($params);

        if(isset($params['page'])) $find->page($params['page']);

        $chars = array();
        $chars_count = 0;
        if(isset($params['realm']) && is_numeric($params['realm'])){
            $find = $find->realm($params['realm']);
            $chars += $find->all();
            $chars_count += $find_count->realm($params['realm'])->count();
        } else {
            foreach ($realms as $realm) {
                $find = $find->realm($realm->id);
                $chars += array_merge($chars ,$find->reload()->all());
                $chars_count += $find_count->realm($realm->id)->count();
            }
        }
        
        $data = array(
            'realms_count' => count($realms),
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
        $char = Character::find()->where(array('guid' => $params['guid']))->realm($params['rid'])->first();
        if ($char->guid == $params['guid']) {
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
                Event::trigger(Event::TYPE_CHARACTER_RECOVER, User::$current->account, $char);
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
    
    function transfer($params){
        $char = Character::find()->where(array('guid' => $params['id']))->realm($params['rid'])->first();
        if($char->guid == $params['id']){
            $realms = Realm::find()->all();

            foreach($realms as $r){
                if($r->id != $char->realm->id) $realmnames[$r->id] = $r->name;
            }
            $this->render(array(
                'character' => $char,
                'realms' => $realmnames
            ));
        } else {
            $this->render_error('404');
        }
    }
    
    function write_dump($params){
        $char = Character::find()->where(array('guid' => $params['guid']))->realm($params['rid'])->first();
        $backup = isset($params['backup']) && $params['backup'];
        
        if ($char->guid == $params['guid']) {
            $answer = $char->write_dump($backup);
            if ($answer == false) {
                $this->render_ajax('error', 'Error on dumping: ' . $char->errors[0]);
            } else {
                Event::trigger(Event::TYPE_CHARACTER_DUMP_LOAD, User::$current->account, $char, ($backup ? '(backup/' : '(') . $char->last_dumpfile_name . ')');
                $this->render_ajax('success', 'Char successfully dumped (' . $answer . ')');
            }   
        } else {
            $this->render_ajax('error', "Can't find character");
        }
    }
    
    function load_dump($params){
        if(isset($params['guid']) && isset($params['rid']) && isset($params['trid']) && isset($params['newname'])){
            $char = Character::find()->where(array('guid' => $params['guid']))->realm($params['rid'])->first();
            $target_realm = Realm::find($params['trid']);
            if ($char->guid == $params['guid'] && $target_realm->id == $params['trid']) {
                $answer = $char->load_dump_to_realm($params['trid'], $params['newname']);
                if ($answer == false) {
                    $this->render_ajax('error', 'Error on dumping: ' . $char->errors[0]);
                } else {
                    Event::trigger(Event::TYPE_CHARACTER_DUMP_LOAD, User::$current->account, $char, "{$target_realm->name}, {$params['newname']}");
                    $this->render_ajax('success', 'Char successfully loaded (' . $answer . ')');
                }   
            } else {
                $this->render_ajax('error', "Can't find character and/or target realm");
            }
        } else {
            $this->render_ajax('error', 'Not all params are set!');
        }
    }
    
    function copy($params){
        if(!empty($params['newname']) && !empty($params['guid']) && !empty($params['rid']) && !empty($params['trid'])){
            $char = Character::find()->where(array('guid' => $params['guid']))->realm($params['rid'])->first();
            $target_realm = Realm::find($params['trid']);
            if ($char->guid == $params['guid'] && $target_realm->id == $params['trid']) {
                $answer = $char->transfer_to_realm($params['trid'], $params['newname']);
                if ($answer == false) {
                    $this->render_ajax('error', 'Error on dumping: ' . $char->errors[0]);
                } else {
                    Event::trigger(Event::TYPE_CHARACTER_TRANSFER, User::$current->account, $char->accountobj, "{$char->name} / {$char->realm->name} -> {$params['newname']} / {$target_realm->name}");
                    $this->render_ajax('success', 'Char successfully dumped (' . $answer . ')');
                }   
            } else {
                $this->render_ajax('error', "Can't find character and/or target realm");
            }
        } else {
            $this->render_ajax('error', "Not all fields are set!");
        }
        
    }
    
    function delete($params){
        $char = Character::find()->where(array('guid' => $params['id']))->realm($params['rid'])->first();
        if($char->guid == $params['id']){
            $this->render(array(
                'character' => $char
            ));
        } else {
            $this->render_error('404');
        }
    }
    
    function erase($params){
        $char = Character::find()->where(array('guid' => $params['guid']))->realm($params['rid'])->first();
        if ($char->guid == $params['guid']) {
            $bu_answer = $char->write_dump(true);
            if(isset($params['hard']) && $params['hard'] == 1){
                if($bu_answer != false){
                    $answer = $char->erase(true);
                } else {
                    $this->render_ajax('error', 'Can\'t backup Char! Deleting process canceld (' . $char->errors[0] .')');
                    return false;
                }
            } else {
                $answer = $char->erase(false);
            }
            
            if($answer != false){
                Event::trigger(Event::TYPE_CHARACTER_DELETE, User::$current->account, $char, (isset($params['hard']) && $params['hard'] == 1 ? 'hard' : 'soft'));
                $this->render_ajax('success', 'Char successfully erased! ' . $answer);
            } else {
                $this->render_ajax('error', 'Can\'t delete Char! ' . $char->errors[0]);  
            }
        } else {
            $this->render_ajax('error', 'Char not found!');
        }
    }

}
