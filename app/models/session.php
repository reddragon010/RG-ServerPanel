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

class Session extends BaseModel {
    static $dbname = 'web';
    static $table = 'sessions';
    static $primary_key = 'session_id';
    static $plural = 'sessions';
    static $fields = array(
        'session_id',
        'http_user_agent', 
        'session_data',
        'session_expire',
        'user_id',
        'current_url',
        'current_ip'
    );
    
    function get_account(){
        if($this->user_id){
            return Account::find($this->user_id);
        } else {
            return new Account(array('username' => 'GUEST'),true);
        }
    }
    
    static public function write_user_info(){
        $session_id = session_id();
        $session = Session::find($session_id);
        if($session){
            $session->user_id = $_SESSION['userid'];
            $session->current_url = Request::$url;
            $session->current_ip = $_SERVER['REMOTE_ADDR'];
            $session->save();
        }
        return true;
    }
}
