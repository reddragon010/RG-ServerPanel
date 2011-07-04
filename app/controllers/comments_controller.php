<?php

class CommentsController extends BaseController {
    var $before = array(
        'check_login'
    );
    /*
    function index($params=array()) {
        $accounts = Comment::find('all', array('conditions' => $params));
        $comment_count = Comment::count(array('conditions' => $params));
        $this->render(array('comments' => $accounts, 'comment_count' => $comment_count));
    }

    function show($params) {

    }
    */
    function add($params) {
        $this->render(array('account_id' => $params['account_id']));
    }

    function create($params) {
        if(empty($params['title'])){
           $this->render_ajax('error', 'Title missing');
        } else {
            if(!isset($params['content'])){
                $params['content'] = '';
            }
            if(Comment::create($params)){
                $this->render_ajax('success', 'Comment Created');
            } else {
                $this->render_ajax('error','Error! Comment creation failed');
            }
            
        }
    }

    function edit($params) {
        global $current_user;
        $comment = Comment::find($params['id']);
        if($comment){
            if($comment->author_id == $current_user->id){
                $this->render(array('comment' => $comment));
            } else {
                $this->render_ajax('error','You are not allowed to change this comment!');
            }
        } else {
            $this->render_ajax('error','Comment not found');
        }    
    }

    function update($params) {
        global $current_user;
        $comment = Comment::find($params['id']);
        if($comment){
            if($comment->author_id == $current_user->id){
                if($comment->update($params)){
                    $this->render_ajax('success', 'updated');
                } else {
                    $this->render_ajax('error','Update failed');
                }
            } else {
                $this->render_ajax('error','You are not allowed to change this comment!');
            }
        } else {
            $this->render_ajax('error','Comment not found');
        }
    }
}
