<?php

class ObjectStore {
    private static $store = array();
    
    public static function gen_key($object){
        $key = '';
        if(is_string($object)){
            return $object;
        } elseif(is_numeric($object)){
            return (string)$object;
        } elseif(is_array($object)){
            foreach($object as $item){
                $key .= self::gen_key($item);
            }
            return $key;
        } else {
            return serialize($object);
        }
    }
    
    public static function put($key,$value){
        $hash = self::gen_hash($key);
        Debug::add('Filling Cache on ' . $hash);
        self::$store[$hash] = $value;
    }
    
    public static function get($key){
        $hash = self::gen_hash($key);
        if(isset(self::$store[$hash])){
            Debug::add('Hit Cache on ' . $hash);
            return self::$store[$hash];
        } else {
            Debug::add('Failed Cache on ' . $hash);
            return false;
        }
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
        return (string)crc32($key);
    }
}
