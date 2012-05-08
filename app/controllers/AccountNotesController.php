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

class AccountNotesController extends ApplicationController {
    function edit($params){
        if(isset($params['account_id'])){
            $note = AccountNote::find($params['account_id']);
            $this->render(array(
                'account_id' => $params['account_id'],
                'note' => $note
            ));
        } else {
            $this->flash('error', 'No AccountID set!');
            $this->redirect_back();
        }
    }
    
    function update($params){
        $params['updated_by'] = User::$current->id;
        $note = AccountNote::find($params['account_id']);
        if($note){
            $params['updated_by'] = User::$current->id;
            if($note->update($params)){
                Event::trigger(Event::TYPE_ACCOUNT_NOTE, User::$current, $note->account);
                $this->render_ajax('success', 'Note updated');
            } else {
                $errormsg = isset($note->errors[0]) ? $note->errors[0] : '';
                $this->render_ajax('error', 'Error on update ' . $errormsg);
            }
        } else {
            if(AccountNote::create($params)){
                $this->render_ajax('success', 'Note created');
            } else {
                $errormsg = isset($note->errors[0]) ? $note->errors[0] : '';
                $this->render_ajax('error', 'Error on creation ' . $errormsg);
            }
        }
    }
}

?>
