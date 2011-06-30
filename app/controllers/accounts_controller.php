<?php

class AccountsController extends BaseController {

    var $before = array(
        'check_login'
    );

    function index($params=array()) {
        $accounts = Account::find('all', array('conditions' => $params));
        $acc_count = Account::count(array('conditions' => $params));
        $this->render(array('accounts' => $accounts, 'acc_count' => $acc_count));
    }

    function show($params) {
        $account = Account::find($params['id']);
        $this->render(array(
            'account' => $account,
            'characters' => $account->characters,
            'same_ip_accounts' => $account->accounts_with_same_ip,
            'bans' => $account->bans,
            'comments' => $account->comments
        ));
    }

    function add() {
        $this->render();
    }

    function create($params) {
        if (isset($params['username']) && isset($params['password'])) {
            $params['flags'] = "2";
            if ($user = User::create($params)) {
                $this->flash('success', 'Thank you for registering, please login!');
                $this->render_ajax('success', "Thank you for registering, please login!");
            } else {
                $errors = array();
                foreach ($user->errors AS $error) {
                    $errors .= $error . "<br />";
                }
                $this->render_ajax('error', $errors);
            }
        } else {
            $this->render_ajax('error', 'Error');
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
            if($account->update($params)){
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

    function password_lost($params) {
        if (isset($params['email'])) {
            if (!empty($params['email']) && $user->userid == NULL) {
                $user_id = userid_by_email($params['email']);
                if ($user_id) {
                    $user = new User;
                    $user->loadUser($user_id, false);
                    if ($user->send_reset_password()) {
                        flash('success', 'E-Mail wurde verschickt');
                    } else {
                        flash('error', 'E-Mail konnte nicht gesendet werden');
                    }
                } else {
                    flash('error', 'E-Mail Adresse konnte nicht gefunden werden');
                }
            } else {
                flash('error', 'Du bist eingeloggt!? Wie kann man da sein Passwort verlieren??');
                header('Location: index.php');
            }
        }
        $this->render('password_lost.tpl', array());
    }

    function password_reset() {
        if (isset($params['key'])) {
            if (User::validate_reset_password_key($params['key'])) {
                $this->render('password_reset.tpl', array('key' => $params['key']));
            }
        } elseif (isset($params['password']) && isset($params['password_confirm'])) {
            if ($_POST['password'] == $params['password_confirm']) {
                if (User::reset_password($params['key'], $params['password'])) {
                    $this->flash('success', 'Passwort wurde erfolgreich geändert! Du kannst dich jetzt einloggen');
                } else {
                    $this->flash('error', 'Der Key ist ungültig!');
                }
                header('Location: index.php');
            } else {
                $this->flash('error', 'die Passwörter müssen gleich sein!');
            }
        } else {
            header('Location: index.php');
        }
        $this->render('password_reset.tpl', array('key' => $params['key']));
    }

}
