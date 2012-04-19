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
        GenericLogger::debug("Checking permission with '$role' on '$controller / $action'");

        if(!isset(self::$roles[$role])){
            self::$roles[$role] = self::load_role_permissions($role);
            GenericLogger::debug(self::$roles[$role], 'Permission-Dump', 'Permissions');
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
            GenericLogger::debug("Couldn't find permission falling back to default => ".(string)$allowed);
        }

        return $allowed;
    }
    
    private static function parse_permissions($perms,$child=false){
        $allowed = null;
        if(is_array($perms) && $child && isset($perms[$child])){
            $allowed = $perms[$child];
            GenericLogger::debug("Found permission on action-level => ".(string)$allowed);
        } elseif(is_bool($perms) || is_numeric($perms)) {
            $allowed = $perms;
            GenericLogger::debug("Found permission on controller-level => ".(string)$allowed);
        }
        return $allowed;
    }
}
