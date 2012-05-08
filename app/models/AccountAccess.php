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

class AccountAccess extends ApplicationModel {
    static $dbname = 'login';
    static $table = 'account_access';
    static $fields = array('id', 'gmlevel', 'realmid');
    static $per_page = 1000;
    
    static $relations = array(
        'account' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'id'
        ),
        'realm' => array(
            'model' => 'Realm',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'realmid'
        )
    );
    
    public function validate() {
        if (!isset($this->id) || $this->id == '') {
            $this->errors[] = "Account is not defined!";
            return false;
        }
        if (!isset($this->realmid) || $this->realmid == '') {
            $this->errors[] = "Realm is not defined!";
            return false;
        }
        if (!isset($this->gmlevel) || $this->gmlevel == '') {
            $this->errors[] = "GM-Level is not defined!";
            return false;
        }
        if($this->new){
            $doup_check = AccountAccess::find()->where(array('id' => $this->id, 'realmid' => $this->realmid))->first();
            if(!empty($doup_check)){
                $this->errors[] = "Can't give multible AccessLevels to the same User on the same Realm";
                return false;
            }
        }
        
        return true;
    }
}
