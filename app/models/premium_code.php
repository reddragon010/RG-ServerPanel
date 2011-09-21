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

class PremiumCode extends BaseModel {
    static $dbname = 'joomla';
    static $table = 'jos_rgpremium_codes';
    static $fields = array(
        'userid', 
        'code', 
        'used', 
        'for'
    );
    static $primary_key = 'code';
    static $name_field = 'code';
    
    public function get_name(){
        return $this->code;
    }
    
    public function validate(){
        if(empty($this->code) || empty($this->userid) || empty($this->for)){
            $this->errors[] = "All fields needs to be filled";
            return false;
        }      
        return true;
    }
    
    public function invalidate(){
        if($this->used == '1'){
            $this->errors[] = "Code already used";
            return false;
        }
        $this->used = '1';
        return $this->save();
    }
    
    public function renew(){
        if($this->used == '1'){
            $this->errors[] = "Code already used";
            return false;
        }
        $code = $this->generate_code();
        $new_code_obj = PremiumCode::create(array(
            'userid' => $this->userid, 
            'used' => 0, 
            'for' => $this->for,
            'code' => $code
        ));
        
        if($new_code_obj && $this->invalidate()){
            return $code;
        } else {
            $this->errors[] = "Can't create new code or invalidate the old one";
            return false;
        }
        
    }
    
    private function generate_code(){
	$code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'), 0, 8);
	$premcode = PremiumCode::find()->where(array('code' => $code))->first();
	
	if($premcode) 
            $code = $this->generate_code();
        
        return $code;
    }
}
