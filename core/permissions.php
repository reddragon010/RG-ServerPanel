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

    public static function check_permission($controller,$action,$role='guest'){
        $param_controller = strtolower(str_replace('Controller', '', $controller));
        GenericLogger::debug("Checking permission on '$role' for '$param_controller / $action'", $controller);

        if(!method_exists($controller,$action)){
            GenericLogger::debug("Action $action not found", $controller);
            return false;
        }

        if(!isset(self::$roles[$role])){
            self::$roles[$role] = self::load_role_permissions($role);
        }
        $perms = self::$roles[$role];
        GenericLogger::debug($perms, $controller);

        if(!is_null($perms)){
            $allowed = self::parse_permission_set($perms, $param_controller, $action);

            if($allowed == null){
                $allowed = self::get_default_permissions($role);
                GenericLogger::debug('No permission found! Falling back to default: ' . var_export($allowed, true), $controller);
            }

            return $allowed;
        } else {
            throw new Exception("Can't resolve permission");
        }
    }

    private static function load_role_permissions($role){
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
        if(isset(self::$roles[$role]['default']))
            return self::$roles[$role]['default'];
        else
            return false;
    }
    
    private static function parse_permission_set($controller_perms,$action){
        if(is_array($controller_perms) && $action && isset($controller_perms[$action])){
            GenericLogger::debug("Found permission on action-level", $action);
            return $controller_perms[$action];
        } elseif(is_bool($controller_perms) || is_numeric($controller_perms)) {
            GenericLogger::debug("Found permission on controller-level", $action);
            return $controller_perms;
        } else {
            GenericLogger::debug("Couldn't find permission", $action);
            return null;
        }
    }
}
