<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.04.12
 * Time: 22:01
 * To change this template use File | Settings | File Templates.
 */

class News extends ApplicationModel
{
    static $dbname = 'web';
    static $table = 'news';
    static $fields = array(
        'id',
        'author_id',
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
        )
    );

    static $per_page = 5;

    function scope_sticky($find){
        return $find->where(array('sticky' => 1));
    }

    function validate(){
        if (!isset($this->author_id) || $this->author_id == '') {
            $this->errors[] = "Author is not defined!";
            return false;
        }
        if (empty($this->title)){
            $this->errors[] = "Title is empty";
            return false;
        }
        if (empty($this->content)){
            $this->errors[] = "Content is empty";
            return false;
        }

        return true;
    }
}
