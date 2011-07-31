<?php

class ObjectStore {
    private static $store = array();
    
    public static function gen_key($object){
        $key = '';
        if(is_string($object)){
            return $object;
        } elseif(is_numeric($object)){
            return strval($object);
        } else {
            return serialize($object);
        }
    }
    
    public static function put($key,$value){
        Debug::add('Filling Cache on Key:' . $key);
        $hash = self::gen_hash($key);
        self::$store[$hash] = $value;
        Debug::stopTimer();
    }
    
    public static function get($key){
        Debug::add('Geting Cache on Key:' . $key);
        $hash = self::gen_hash($key);
        if(isset(self::$store[$hash])){
            $op = self::$store[$hash];
        } else {
            $op = false;
        }
        Debug::stopTimer();
        return $op;
    }
    
    public static function delete($key){
        $hash = self::gen_hash($key);
        if(isset(self::$store[$hash]))
                unset(self::$store[$hash]);
    }
    
    public static function pop($key){
        $op = self::get($key);
        self::delete($key);
        return $op;
    }
    
    public static function check($key){
        $hash = self::gen_hash($key);
        return isset(self::$store[$hash]);
    }
    
    private static function gen_hash($key){
        return crc32($key);
    }
}
