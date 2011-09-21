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

class SessionController extends BaseController {

    function index($params){
        $now = time();
        $data = Session::find()->where(array("session_expire > '$now'"))->all();
        if(isset($params['partial'])){
            $this->render_partial('sessions',array('sessions' => $data));
        } else {
            $this->render(array('sessions' => $data));
        }
    }
    
    function add() {
        //User::clear_session();
        $this->render();
    }

    function create($params) {
        $success = false;
        if (isset($params['login_username']) && isset($params['login_password']) && !empty($params['login_username']) && !empty($params['login_password'])) {
            $user = new User(strtoupper($params['login_username']), $params['login_password']);
            if ($user->login()) {
                $this->flash('success', 'Login successful!');
                Event::trigger(Event::TYPE_USER_LOGIN, $user->account);
                $success = true;
            } else {
                $this->flash('error', "Benutzername/Passwort nicht existent oder inkorrekt!");
                $success = false;
            }
        } else {
            $this->flash('error', "Name oder Passwort wurden nicht angegeben!");
            $success = false;
        }
        if($success){
            $this->redirect_to(array('home', 'index'));
        } else {
            $this->redirect_to_login();
        }
    }

    function delete() {
        if (User::$current->logout()) {
            Event::trigger(Event::TYPE_USER_LOGOUT, User::$current->account);
            session_start();
            $this->flash('success', "erfolgreich ausgeloggt!");
        }
        $this->redirect_to(array('session', 'add'));
    }

}
