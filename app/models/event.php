<?php
class Event extends BaseModel {
    static $dbname = 'web';
    static $table = 'events';
    static $fields = array(
        'id', 
        'type', 
        'account_id', 
        'target_class', 
        'target_id',
        'created_at',
        'text'
    );
    
    const TYPE_USER_LOGIN   = 101;
    const TYPE_USER_LOGOUT  = 102;
        
    const TYPE_ACCOUNT_EDIT     = 201;
    const TYPE_ACCOUNT_COMMENT  = 202;
    const TYPE_ACCOUNT_BAN      = 203;
    const TYPE_ACCOUNT_UNBAN    = 204;
    const TYPE_ACCOUNT_LOCK     = 205;
    const TYPE_ACCOUNT_UNLOCK   = 206;
    
    const TYPE_CHARACTER_EDIT   = 301;
    
    public static $types = array(
        'TYPE_USER_LOGIN' => 'logged in',
        'TYPE_USER_LOGOUT' => 'logged out',
        'TYPE_ACCOUNT_EDIT' => 'edited account',
        'TYPE_ACCOUNT_COMMENT' => 'commented account',
        'TYPE_ACCOUNT_BAN' => 'banned account',
        'TYPE_ACCOUNT_UNBAN' => 'unbanned account',
        'TYPE_ACCOUNT_LOCK' => 'locked account',
        'TYPE_ACCOUNT_UNLOCK' => 'unlocked account',
        'TYPE_CHARACTER_EDIT' => 'edited character'
    );
    
    public static function trigger($type, $account, $target=NULL, $text=NULL){
        $event = new Event();
        $event->type = $type;
        $event->account_id = $account->id;
        if(is_object($target) && !is_array($target)){
            $event->target_class = get_class($target);
            $event->target_id = $target->{$target::$primary_key};
        } elseif(is_array($target)){
            $event->target_class = $target[0];
            $event->target_id = $target[1];
        }
        if(is_string($text)){
            $event->text = $text;
        }
        return $event->save();
    }
    
    
    
    public function get_account(){
        return Account::find($this->account_id);
    }
    
    public function get_target(){
        if(is_string($this->target_class)){
            $class = $this->target_class;
            $obj = $class::find($this->target_id);
        } else {
            $obj = new stdClass();
            $obj->name = '';
            $obj->id = '';
        }
        return $obj;
    }
    
    public function get_description(){
        $desc = i18n::get('events',$this->type);
        $subst = array(
            '%user%' => $this->account->username,
            '%userid%' => $this->account->id,
            '%targetid%' => $this->target_id,
            '%targetname%' => $this->target->name,
            '%text%' => $this->text
        );
        return str_replace(array_keys($subst), array_values($subst), $desc);
    }
}
