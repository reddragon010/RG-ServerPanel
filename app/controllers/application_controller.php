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
        if (!isset(User::$current) || empty(User::$current)) {
            $this->flash('error', 'you are not logged in or your session timed out - please relog');
            $this->redirect_to_login();
            return false;
        }
        return true;
    }
    
    function check_permission(){
        $controller = get_class(Router::$controller);
        $action = Router::$action;
        if(isset(User::$current)){
            $allowed = User::$current->is_permitted_to($action, $controller); 
        } else {
            $allowed = Permissions::check_permission($controller, $action);
        }
        
        if(!$allowed){
            if(isset(User::$current)){
                $this->error(array('status' => '401'));
            } else {
                $this->flash('error', 'Please LogIn');
                $this->redirect_to_login();
            }
            return false;
        }
        return true;
    }
    
    function redirect_to_login(){
        $this->redirect_to(array('session', 'add'));
    }
    
    function render($view,$data=array()){
        $tpl = Template::instance("application");
        $tpl->render($view, $data);
    }
    
    function error($params){
        $this->set_header_status($params['status']);
        $this->render($params['status']);
    }

}
