<?php

class Comment extends BaseModel {
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
