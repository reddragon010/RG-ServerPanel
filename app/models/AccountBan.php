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

class AccountBan extends ApplicationModel {
    static $dbname = 'login';
    static $table = 'account_banned';
    static $fields = array('id', 'bandate', 'unbandate', 'bannedby', 'banreason', 'active');
    static $relations = array(
        'account' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'id'
        ),
        'banning_account' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'bannedby'
        )
    );
    
    public function get_name(){
        return $this->account->username;
    }
    
    public function validate() {
        if (!isset($this->id) || $this->id == '') {
            $this->errors[] = "Account is not defined!";
            return false;
        }
        if (!isset($this->bandate)) {
            $this->errors[] = "Ban-Date is not defined!";
            return false;
        }
        if (!isset($this->bannedby) || $this->bannedby == '') {
            $this->errors[] = "Banning Account is not defined!";
            return false;
        }
        if ($this->new && $this->unbandate < time() && $this->unbandate != 0){
            $this->errors[] = "Unbandate is in the past";
            return false;
        }
        $banned_check = AccountBan::find()->where(array('id' => $this->id, 'active' => '1'))->first();
        if($this->active == 1 && $banned_check){
            $this->errors[] = "Account already banned!";
            return false;
        } elseif( $this->active == 0 && !$banned_check) {
            $this->errors[] = "Account is not banned!";
            return false;
        }
        
        return true;
    }
}
