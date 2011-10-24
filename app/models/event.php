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

class Event extends BaseModel {
    static $dbname = 'web';
    static $table = 'events';
    static $fields = array(
        'id', 
        'type', 
        'account_id', 
        'target_id',
        'target_class',
        'target_obj', 
        'created_at',
        'text'
    );
    
    static $relations = array(
        'account' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'account_id'
        )
    );
    
    const TYPE_USER_LOGIN   = 101;
    const TYPE_USER_LOGOUT  = 102;
        
    const TYPE_ACCOUNT_EDIT     = 201;
    const TYPE_ACCOUNT_COMMENT  = 202;
    const TYPE_ACCOUNT_BAN      = 203;
    const TYPE_ACCOUNT_UNBAN    = 204;
    const TYPE_ACCOUNT_LOCK     = 205;
    const TYPE_ACCOUNT_UNLOCK   = 206;
    const TYPE_ACCOUNT_NOTE     = 207;
    
    const TYPE_CHARACTER_EDIT   = 301;
    
    const TYPE_PREMCODE_VERIFY = 401;
    const TYPE_PREMCODE_INVALIDATE = 402;
    const TYPE_PREMCODE_RENEW = 403;
    const TYPE_PREMCODE_CREATE = 404;
    
    public static $types = array(
        'TYPE_USER_LOGIN'       => 'logged in',
        'TYPE_USER_LOGOUT'      => 'logged out',
        'TYPE_ACCOUNT_EDIT'     => 'edited account',
        'TYPE_ACCOUNT_COMMENT'  => 'commented account',
        'TYPE_ACCOUNT_BAN'      => 'banned account',
        'TYPE_ACCOUNT_UNBAN'    => 'unbanned account',
        'TYPE_ACCOUNT_LOCK'     => 'locked account',
        'TYPE_ACCOUNT_UNLOCK'   => 'unlocked account',
        'TYPE_ACCOUNT_NOTE'     => 'updated account-note',
        'TYPE_CHARACTER_EDIT'   => 'edited character',
        'TYPE_PREMCODE_VERIFY'  => 'verified premium-code',
        'TYPE_PREMCODE_INVALIDATE'   => 'invalidated premium-code',
        'TYPE_PREMCODE_RENEW'   => 'renewed premium-code',
        'TYPE_PREMCODE_CREATE'  => 'created premium-code',
    );
    
    public static function trigger($type, $account, $target=NULL, $text=NULL){
        $event = new Event();
        $event->type = $type;
        $event->account_id = $account->id;
        if(is_object($target)){
            $event->target_id = $target->{$target::$primary_key};
            $event->target_class = get_class($target);
            $event->target_obj = serialize($target);
        }
        if(is_string($text)){
            $event->text = $text;
        }
        return $event->save();
    }
    
    public function get_target(){
        if(!empty($this->target_obj)){
            $obj = unserialize($this->target_obj);
            $class = get_class($obj);
            $obj->pk = $class::$primary_key;
        } else {
            $obj = new stdClass();
            $obj->id = 0;
            $obj->name = '';
            $obj->pk = 'id';
        }
        return $obj;
    }
    
    public function get_description(){
        $desc = i18n::get('events',$this->type);
        $target_class = get_class($this->target);
        $subst = array(
            '%user%' => $this->account->username,
            '%userid%' => $this->account->id,
            '%targetid%' => $this->target->pk,
            '%targetname%' => $this->target->name,
            '%text%' => $this->text
        );
        return str_replace(array_keys($subst), array_values($subst), $desc);
    }
}
