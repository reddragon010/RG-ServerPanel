<?php

class CommentsController extends BaseController {
    var $before = array(
        'check_login'
    );
    
    function index($params){
        if(!isset($params['account_id']) || $params['account_id'] == ''){
            $this->render_error('404');
            return;
        }
        
        $comments = Comment::find()->where($params)->order('created_at DESC');
        
        $data = array(
            'comments' => $comments->all(),
            'comments_count' => $comments->count(),
        );
        
        if(isset($params['partial'])){
            $this->render_partial('shared/comments', $data);
        } else {
            $this->render($data);
        }
    }
    
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
            if(Comment::create($params, &$obj)){
                $account = Account::find()->where(array('id' => $params['account_id']))->first();
                Event::trigger(Event::TYPE_ACCOUNT_COMMENT, User::$current->account, $account, $account->username);
                $this->render_ajax('success', 'Comment Created');
            } else {
                $this->render_ajax('error','Error! ' . $obj->errors[0]);
            }
            
        }
    }

    function edit($params) {
        $comment = Comment::find($params['id']);
        if($comment){
            if($comment->author_id == User::$current->id){
                $this->render(array('comment' => $comment));
            } else {
                $this->render_ajax('error','You are not allowed to change this comment!');
            }
        } else {
            $this->render_ajax('error','Comment not found');
        }    
    }

    function update($params) {
        $comment = Comment::find($params['id']);
        if($comment){
            if($comment->author_id == User::$current->id){
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
