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
    
    function get_author(){
        $author = Account::find('first', array('conditions' => array('id' => $this->author_id)));
        return $author;
    }
    
    function get_account(){
        $acount = Account::find('first', array('conditions' => array('id' => $this->account_id)));
        return $account;
    }
    
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
