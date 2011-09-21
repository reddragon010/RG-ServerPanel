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

class AccountPartner extends BaseModel {
    static $dbname = 'web';
    static $table = 'account_partners';
    static $fields = array('id', 'account_id', 'partner_id', 'comment', 'until');
    
    static $relations = array(
        'account' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'acount_id'
        ),
        'partner' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'partner_id'
        )
    );
    
    public function validate() {
        if (!isset($this->account_id) || $this->account_id == '') {
            $this->errors[] = "Account is not defined!";
            return false;
        }
        if (!isset($this->partner_id) || $this->partner_id == '') {
            $this->errors[] = "Partner is not defined!";
            return false;
        }
        if ($this->partner_id == $this->account_id){
            $this->errors[] = "Selflinking is a bit weired! ... isn't is?";
            return false;
        }
        if($this->new && isset($this->until) && $this->until < time()){
            $this->errors[] = "Until-Date can't be in the past";
            return false;
        }
        
        $double_check = AccountPartner::find()->where(array('(account_id = :account_id AND partner_id = :partner_id) OR (account_id = :partner_id AND partner_id = :account_id)', 'account_id' => $this->account_id, 'partner_id' => $this->partner_id))->first();
        if($double_check && (is_null($double_check->until) || $double_check->until > time()) ){
            $this->errors[] = "Account-Partner Relation already exists";
            return false;
        }

        return true;
    }
}

?>
