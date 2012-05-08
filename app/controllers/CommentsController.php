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

class CommentsController extends ApplicationController {
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
            $params['created_at'] = '#NOW';
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
            if(User::$current->get_role() == "lead-gm" || $comment->author_id == User::$current->id){
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
            if(User::$current->get_role() == "lead-gm" || $comment->author_id == User::$current->id){
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
    
    function delete($params){
        $comment = Comment::find($params['id']);
        if($comment){
            if($comment->destroy()){
                $this->flash('success', 'deleted!');
            } else {
                $this->flash('error','Cant delete file');
            }
        } else {
            $this->flash('error','Comment not found');
        }
        $this->redirect_back();
    }
}
