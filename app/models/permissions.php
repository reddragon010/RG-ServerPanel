<?php

class Permissions {
    private static $fields = array(
        'id'
    );
    
    private static $roles = array(
        0 => 'Guest',
        1 => 'User',
        2 => 'VIP',
        3 => 'TrailGM',
        4 => 'GM',
        5 => 'LeadGM'
    );
    
    private static $default_permissions = array(
        0 => false,
        1 => false,
        2 => false,
        3 => false,
        4 => false,
        5 => true
    );
    
    private static $permissions = array(
        0 => array(
            'session' => array(
                'add' => true,
                'create' => true
            )
        ),
        1 => false,
        2 => 1,
        3 => array(
            'account_acl' => false,
            'account_banns' => true,
            'account_partners' => true,
            'accounts' => true,
            'application' => true,
            'characters' => true,
            'comments' => true,
            'guilds' => true,
            'home' => true,
            'realms' => true,
            'search' => true,
            'session' => true
        ),
        4 => 3,
        5 => 3
    );
    
    public static function get_role_name_by_id($id){
        if(isset(self::$roles[$id])){
            return self::$roles[$id];
        } else {
            return false;
        }
    }
    
    public static function get_role_id_by_name($name){
        $id = array_search($name, self::$roles);
        if($id){
            return $id;
        } else {
            return false;
        }
    }
    
    public static function get_permissions_by_id($id){
        if(isset(self::$permissions[$id])){
            return self::$permissions[$id];
        } else {
            return null;
        }
    }
    
    public static function get_default_permissions_by_id($id){
        if(isset(self::$default_permissions[$id])){
            return self::$default_permissions[$id];
        } else {
            return false;
        }
    }
    
    public static function check_permission($roleid,$controller,$action){ 
        $role_name = self::get_role_name_by_id($roleid);
        Debug::add("Checking permission with '$role_name' on '$controller / $action'");
        $controller = str_replace('Controller', '', $controller);
        $perms = self::get_permissions_by_id($roleid);
        if(isset($perms[$controller])){
            $allowed = self::parse_permissions($perms[$controller], $action);
        } elseif(!is_null($perms)){
            $allowed = self::parse_permissions($perms);
        } else {
            $allowed = self::get_default_permissions_by_id($roleid);
        }
        if(is_numeric($allowed)){
            $linked_role_name = self::get_role_name_by_id($allowed);
            Debug::add("Follow permission-link to $linked_role_name");
            $allowed = self::check_permission($allowed, $controller, $action);
        }
        return $allowed;
    }
    
    private static function parse_permissions($perms,$child=false){
        if(is_array($perms) && $child && isset($perms[$child])){
            $allowed = $perms[$child];
            Debug::add("Found permission on action-level => ".var_export($allowed,true));
        } elseif(is_bool($perms) || is_numeric($perms)) {
            $allowed = $perms;
            Debug::add("Found permission on controller-level => ".var_export($allowed,true));
        } else {
            Debug::error("Wrong Permission-Settings ($child) => ".var_export($perms,true));
        }
        return $allowed;
    }
}
