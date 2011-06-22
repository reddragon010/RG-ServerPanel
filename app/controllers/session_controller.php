<?php
class SessionController extends BaseController {

    function add() {
        User::clear_session();
        $this->render();
    }

    function create($params) {
        if (isset($params['login_username']) && isset($params['login_password']) && !empty($params['login_username']) && !empty($params['login_password'])) {
            $user = new User($params['login_username'], $params['login_password']);
            if ($user->login()) {
                $this->flash('success', 'Login successful!');
                $this->redirect_to(array('home', 'index'));
            } else {
                $this->flash('error', "Benutzername/Passwort nicht existent oder inkorrekt!");
                $this->redirect_to_login();
            }
        } else {
            $this->flash('error', "Name oder Passwort wurden nicht angegeben!");
            $this->redirect_to_login();
        }
        var_dump($user);
    }

    function delete() {
        global $current_user;
        if ($current_user->logout()) {
            session_start();
            $this->flash('success', "erfolgreich ausgeloggt!");
        }
        $this->redirect_to(array('home', 'index'));
    }

}
