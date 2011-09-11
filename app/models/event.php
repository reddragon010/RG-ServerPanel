<?php
class Event extends BaseModel {
    static $dbname = 'web';
    static $table = 'events';
    static $fields = array(
        'id', 
        'type', 
        'account_id', 
        'target_obj', 
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
        'TYPE_CHARACTER_EDIT'   => 'edited character',
        'TYPE_PREMCODE_VERIFY'  => 'verified premium-code',
        'TYPE_PREMCODE_INVALIDATE'   => 'invalidated premium-code',
        'TYPE_PREMCODE_RENEW'   => 'renewed premium-code',
        'TYPE_PREMCODE_CREATE'  => 'created premium-code'
    );
    
    public static function trigger($type, $account, $target=NULL, $text=NULL){
        $event = new Event();
        $event->type = $type;
        $event->account_id = $account->id;
        if(is_object($target)){
            $event->target_obj = serialize($target);
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
