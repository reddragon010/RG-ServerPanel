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

class Comment extends ApplicationModel {
    static $dbname = 'web';
    static $table = 'comments';
    static $fields = array(
        'id', 
        'author_id', 
        'account_id', 
        'title', 
        'content',
        'created_at',
        'updated_at'
    );
    
    static $relations = array(
        'author' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'author_id'
        ),
        'account' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'account_id'
        )
    );
    
    function validate(){
        if (!isset($this->author_id) || $this->author_id == '') {
            $this->errors[] = "Author is not defined!";
            return false;
        }
        if (!isset($this->account_id) || $this->account_id == '') {
            $this->errors[] = "Account is not defined!";
            return false;
        }
        if (empty($this->title)){
            $this->errors[] = "Title is empty";
            return false;
        }
        
        return true;
    }
}
