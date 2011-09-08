<?php

class AccountsController extends BaseController {

    var $before = array(
        'check_login'
    );

    function index($params=array()) {
        if(empty($params['order'])){
            $order = 'id';
        } else {
            $order = $params['order'];
        }
        $accounts = Account::find('all', array('conditions' => $params, 'order' => $order));
        $acc_count = Account::count(array('conditions' => $params));
        $this->render(array('accounts' => $accounts, 'acc_count' => $acc_count));
    }

    function show($params) {
        $account = Account::find($params['id']);
        $this->render(array(
            'account' => $account,
            'same_ip_accounts' => $account->accounts_with_same_ip,
            'partners' => $account->partners
        ));
    }

    function add() {
        $this->render();
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
                Event::trigger(Event::TYPE_ACCOUNT_EDIT, User::$current->account, $account,$account->username);
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
