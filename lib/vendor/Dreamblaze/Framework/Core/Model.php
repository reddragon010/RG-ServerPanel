<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of StupidPrlf.
 *
 *    StupidPrlf is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    StupidPrlf is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with StupidPrlf.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Dreamblaze\Framework\Core;
use Dreamblaze\SqlS\Database_Object;
use Dreamblaze\SqlS\Database_Manager;
use Dreamblaze\SqlS\Query_Builder;

class Model extends Database_Object implements ModelInterface {

    public static $primary_key = 'id';
    public static $per_page = 25;
    public static $relations = array();
    public $errors = array();

    public static function find($pk=null) {
        $find = new QueryFind(get_called_class());
        return $find->find($pk);
    }

    public static function create($params=array(), &$obj=null) {
        $obj = static::build($params, true);
        return $obj->save();
    }

    public function reload() {
        $obj = static::find($this->{static::$primary_key});
        if ($obj) {
            $this->data = $obj->data;
            return true;
        } else {
            return false;
        }
    }

    public function destroy() {
        $table = static::$table;
        $pk = static::$primary_key;
        $sql = "DELETE FROM {$table} WHERE {$pk}='{$this->$pk}'";
        if(isset(static::$dbid)){
            $db = Database_Manager::get_database(static::$dbname,static::$dbid);
        } else {
            $db = Database_Manager::get_database(static::$dbname,null);
        }
        $db->query($sql);
        return true;
    }

    public function update($params) {
        $params = array_filter($params);
        foreach ($params as $param => $val) {
            if ((isset($this->$param) && $this->$param != $val)) {
                $this->$param = $val;
            }
        }
        return $this->save();
    }

    public function save() {
        if(empty($this->modified_data) && !$this->new)
                return true;


        if ($this->validate()) {
            $data = array_intersect_key($this->data, array_flip(static::$fields));
            if ($this->new) {
                $sql = Query_Builder::insert(get_called_class());
                $sql->values($data);
            } else {
                $pk = static::$primary_key;
                $data = array_intersect_key($data, array_flip($this->modified_data));
                $sql = Query_Builder::update(get_called_class());
                $sql->set($data);
                $sql->where(array($pk => $this->$pk));
            }
            
            if (method_exists($this, 'before_save')) {
                if (!$this->before_save(&$sql)){
                    $this->errors[] = "before_save failed";
                    return false;
                }
            }
            
            if(!$sql->execute()){
                $this->error[] = "Save failed on SQL-Level!";
                return false;
            }

            if (method_exists($this, 'after_create') && $this->new) {
                $this->after_create();
            } elseif (method_exists($this, 'after_update')) {
                $this->after_update();
            }
            if (method_exists($this, 'after_save')) {
                $this->after_save();
            }
            return true;
        } else {
            $this->errors[] = "validation failed";
            return false;
        }
    }

    public function validate() {
        return true;
    }

}