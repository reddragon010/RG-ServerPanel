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
    
    const TYPE_USER_LOGIN = 101;
    const TYPE_USER_LOGOUT= 102;
        
    const TYPE_ACCOUNT_EDIT = 201;
    const TYPE_ACCOUNT_COMMENT = 202;
    
    public static function trigger($type, $account, $target=NULL, $text=NULL){
        $event = new Event();
        $event->type = $type;
        $event->account_id = $account->id;
        if($target != NULL){
            $event->target_class = get_class($target);
            $event->target_id = $target->{$target::$pk};
        }
        if($test != NULL){
            $event->text = $text;
        }
        return $event->save();
    }
    
    public function get_account(){
        return Account::find($this->account_id);
    }
    
    public function get_target(){
        $class = $this->target_class;
        switch($class){
            case 'Account':
                $target = Account::find($this->target_id);
                break;
            default :
                $target = false;
                break;
        }
        return $target;
    }
    
    public function get_description(){
        return i18n::get('events',$this->type);
    }
}
