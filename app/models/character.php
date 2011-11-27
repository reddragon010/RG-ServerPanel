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

class Character extends BaseModel {
    static $dbname = 'realm';
    static $dbid = null;
    static $table = 'characters';
    static $primary_key = 'guid';
    static $name_field = 'name';
    static $plural = 'characters';
    static $fields = array(
        'guid',
        'name',
        'online',
        'map',
        'zone',
        'account',
        'race',
        'class',
        'gender',
        'level',
        'money',
        'totaltime',
        'deleteinfos_account',
        'deleteinfos_name',
        'deletedate'
    );
    
    static $relations = array(
        'accountobj' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'account',
        )
    );
    
    public static $per_page = 25;
    public $realm;
    
    public function before_save($sql){
        $sql->dbid = $this->realm->id;
        return true;
    }
    
    public function scope_realm($find, $realm_id){
        $find->dbid = $realm_id;
        $find->additions(array('realm' => Realm::find($realm_id)));
        return $find;
    }
    
    static function charname_unused($charname, $realmid){
        $char = Character::find()->realm($realmid)->where(array('name' => $charname))->first();
        if(isset($char->name) && $char->name == $newname){
            return false;
        } else {
            return true;
        }
    }
    
    public function get_deleted(){
        return  !empty($this->deleteinfos_name) && 
                !empty($this->deleteinfos_account) && 
                empty($this->name) && 
                $this->account_id == 0;
    }
    
    public function recover(){
        if($this->deleted){
            $this->name = $this->deleteinfos_name;
            $this->account = $this->deleteinfos_account;
            $this->deleteinfos_name = null;
            $this->deleteinfos_account = null;
            $this->deletedate = null;
            return $this->save();
        } else {
            $this->errors[] = "Char is not deleted";
            return false;
        }
    }
    
    public function kick(){
        if($this->realm->soap){
            try{
                $answer = $this->realm->soap->kick($this->name);
            } catch(Exception $e){
                $this->errors[] = $e->getMessage();
                return false;
            }
        }
        return $answer;
    }
    
    public function write_dump($backup=false){
        if($this->realm->soap){
            $filepath = $this->get_dumpfile_path($this->realm->id, $this->guid, $backup);
            if($filepath){
                try{
                    $result = $this->realm->soap->write_char_dump($this->guid, $filepath);
                    return $result;
                } catch(Exception $e){
                    $this->errors[] = $e->getMessage();
                }
            } else {
                $this->errors[] = 'Can\'t create filepath';
            }
        } else {
            $this->errors[] = "Soap-Connection error";
        }
        return false;
    }
    
    public function load_dump_to_realm($realmid, $newname){
        if(empty($newname)) $newname = $this->name;
        
        if(Character::charname_unused($newname, $realmid)){
            $realm = Realm::find($realmid);

            if($realm->soap){
                $filepath = $this->get_dumpfile_path($this->realm->id, $this->guid);
                if($filepath){
                    try{
                        $result = $realm->soap->load_char_dump($filepath, $this->account, $newname);
                    } catch(Exception $e){
                        $this->errors[] = $e->getMessage();
                        return false;
                    }
                    sleep(2);
                    $newchar = Character::find()->realm($realmid)->where(array('name' => $newname))->first();
                    if(isset($newchar->name) && $newchar->name == $newname){
                        return $result;
                    } else {
                        $this->errors[] = "Char seems to be loaded but can't find it! ($result)";
                    }
                }
            }   
        } else {
            $this->errors[] = "Charname already used";
        }
        return false;
    }
    
    public function get_dumpfile_path($realmid, $guid, $backup=false){
        try {
            $dumpdir = Environment::get_value('dump_dir');
            if(substr($dumpdir, strlen($dumpdir), 1) != '/') $dumpdir .= '/';
        } catch(Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
        $filenameparts = array(
            $realmid,
            $guid,
            'tcdump.sql'
        );
        $path = $dumpdir;
        if($backup) $path .= 'backup/' . time() . '_';
        $path .= join('_', $filenameparts);
        return $path;
    }
    
    public function erase(){
        if($this->realm->soap){
            try{
                $result = $this->realm->soap->delete_char($name);
                return $result;
            } catch(Exception $e){
                $this->errors[] = $e->getMessage();
            }
        }
    }
    
    public function validate(){
        if ($this->online){
            $this->errors[] = "Account is online! Please SaveBan and Kick it to avoid 'The End Of The WO-World'!";
            return false;
        }
        
        if(empty($this->account) || $this->account == 0){
            $this->errors[] = "Owner-Account can't be empty";
            return false;
        }
        
        if(!empty($this->account)){
            $account = Account::find($this->account);
            if(empty($account->username)){
                $this->errors[] = "Owner-Account not found";
                return false;
            }
        }
        
        return true;
    }

}