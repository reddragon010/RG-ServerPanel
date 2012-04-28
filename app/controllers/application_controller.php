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

class ApplicationController extends BaseController {

    var $before_all = array('load_user','check_permission');
    var $after_all = array('save_session');
    
    function save_session(){
        return Session::write_user_info();
    }
    
    function load_user() {
        return User::load_current_user();
    }

    function check_login() {
        GenericLogger::debug('Checking login');
        if (!isset(User::$current) || empty(User::$current)) {
            GenericLogger::warning("Wrong login from " . $_SERVER['REMOTE_ADDR']);
            $this->flash('error', 'you are not logged in or your session timed out - please relog');
            $this->redirect_to_login();
            return false;
        }
        return true;
    }
    
    function check_permission(){
        GenericLogger::debug('Checking Permissions');
        $controller = get_class($this);
        $action = $this->params['action'];
        if(isset(User::$current)){
            $allowed = User::$current->is_permitted_to($action, $controller);
        } else {
            $allowed = Permissions::check_permission($controller, $action);
        }
        
        if(!$allowed){
            isset(User::$current) ? $uname = User::$current->name : $uname = "Guest";
            GenericLogger::warning($uname . " was not allowed to access " . $_SERVER['REQUEST_URI']);
            if(isset(User::$current)){
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                   $this->render_ajax('error', '401 - Not Allowed');
                } else {
                   $this->error(array('status' => '401')); 
                }
                
            } else {
                $this->flash('error', 'Please LogIn');
                $this->redirect_to_login();
            }
            return false;
        }
        return true;
    }
    
    function redirect_to_login(){
        if($this->params['controller'] != 'session' && $this->params['action'] != 'add')
            $this->redirect_to(array('session', 'add'));
        else
            $this->render_error(401);
    }
    
    function error($params){
        $this->render_error($params['status']);
    }

}
