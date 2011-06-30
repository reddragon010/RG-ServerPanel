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
}
