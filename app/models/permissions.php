<?php

class Permissions {
    private static $roles = array();
    
    private static function load_role_permissions($role){
        $roles_perms = array();
        $perms = Config::instance('permissions')->get_value($role);
        if (isset($perms['inherit_from'])) {
            $linked_perms = self::load_role_permissions($perms['inherit_from']);
            $result = self::merge_permissions($linked_perms,$perms);
        } else {
            $result = $perms;
        }
        return $result;
    }
    
    private static function merge_permissions() {
        $arrays = func_get_args();
        $merged = array();
        while ($arrays) {
            $array = array_shift($arrays);
            if (!is_array($array)) {
                return $arrays[0];
            }
            if (!$array)
                continue;
            foreach ($array as $key => $value){
                if (is_string($key)){
                    if (is_array($value) && array_key_exists($key, $merged) && is_array($merged[$key])){
                        $merged[$key] = call_user_func(array('self','merge_permissions'), $merged[$key], $value);
                    } else {
                        $merged[$key] = $value;
                    }
                } else {
                    $merged[] = $value;
                }
            }
        }
        return $merged;
    }
    
    private static function get_default_permissions($role){
        if(isset(self::$roles[$role]['default'])){
            $allowed = self::$roles[$role]['default'];
        } else {
            $allowed = false;
        }
        return $allowed;
    }
    
    public static function check_permission($controller,$action,$role='guest'){
        $controller = strtolower(str_replace('Controller', '', $controller));
        Debug::add("Checking permission with '$role' on '$controller / $action'");
        
        if(!isset(self::$roles[$role])){
            self::$roles[$role] = self::load_role_permissions($role);
            Debug::dump(self::$roles[$role]);
        }
        $perms = self::$roles[$role];
        
        $allowed = null;
        if(isset($perms[$controller])){
            $allowed = self::parse_permissions($perms[$controller], $action);
        } elseif(!is_null($perms)){
            $allowed = self::parse_permissions($perms,false);
        } 
        
        if(is_null($allowed)) {
            $allowed = self::get_default_permissions($role);
            Debug::add("Couldn't find permission falling back to default => ".var_export($allowed,true));
        }

        return $allowed;
    }
    
    private static function parse_permissions($perms,$child=false){
        $allowed = null;
        if(is_array($perms) && $child && isset($perms[$child])){
            $allowed = $perms[$child];
            Debug::add("Found permission on action-level => ".var_export($allowed,true));
        } elseif(is_bool($perms) || is_numeric($perms)) {
            $allowed = $perms;
            Debug::add("Found permission on controller-level => ".var_export($allowed,true));
        }
        return $allowed;
    }
}
