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
        if(!is_array($data)) $data = array();
        $unser_data += self::unserialize($data['session_data']);
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
