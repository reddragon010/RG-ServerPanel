<?php
class SessionController extends BaseController {

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
        global $current_user;
        if ($current_user->logout()) {
            Event::trigger(Event::TYPE_USER_LOGOUT, $current_user->account);
            session_start();
            $this->flash('success', "erfolgreich ausgeloggt!");
        }
        $this->redirect_to(array('session', 'add'));
    }

}
