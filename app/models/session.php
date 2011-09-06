<?php

class Session extends BaseModel {
    static $dbname = 'web';
    static $table = 'sessions';
    static $primary_key = 'session_id';
    static $plural = 'sessions';
    static $fields = array(
        'session_id',
        'http_user_agent', 
        'session_data',
        'session_expire'
    );
    
    public function __construct($data=array(), $new=true){
        $data += self::unserialize($data['session_data']);
        parent::__construct($data, $new);
    }
    
    function get_account(){
        if($this->userid){
            return Account::find($this->userid);
        } else {
            return null;
        }
    }
    
    public static function unserialize($string){
        $result = array();
        $string = ';' . $string;
        $keyreg = '/;([^|{}"]+)\|/';
        $matches = array();
        preg_match_all($keyreg, $string, $matches);
        if(isset($matches[1])){
            $keys = $matches[1];
            $values = preg_split($keyreg, $string);
            if(count($values) > 1){
                array_shift($values);
            }
            $values = array_map(function($elem){
                return unserialize($elem);
            }, $values);
            $result = array_combine($keys, $values);
        }
        return $result;
    }
}
