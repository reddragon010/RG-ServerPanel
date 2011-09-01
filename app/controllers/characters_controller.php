<?php
class CharactersController extends BaseController {

    var $before = array(
        'check_login'
    );

    function index($params=array()) {
        $realms = Realm::find('all');
        $chars = array();
        $chars_count = 0;
        foreach($realms as $realm){
            $params['realm_id'] = $realm->id;
            $chars += Character::find('all', array('conditions' => $params));
            $chars_count += Character::count(array('conditions' => $params));
        }
        
        $data = array(
            'chars_count' => $chars_count,
            'characters' => $chars,
        );
        
        if(isset($params['partial'])){
            $this->render_partial('shared/characters', $data);
        } else {
            $this->render($data);
        }
    }
    
    function show($params){
        $char = Character::find('first',array('conditions' => array('guid' => $params['id'], 'realm_id' => $params['rid'])));
        $this->render(array('character' => $char));
    }
    
    function edit($params){
        $char = Character::find('first',array('conditions' => array('guid' => $params['id'], 'realm_id' => $params['rid'])));
        $this->render(array('character' => $char));
    }
    
    function update($params){
        $char = Character::find('first',array('conditions' => array('guid' => $params['guid'], 'realm_id' => $params['rid'])));
        if($char){
            if($char->update($params)){
                $this->render_ajax('success','Character updated');
            } else {
                if(isset($char->errors[0])){
                    $this->render_ajax('error', $char->errors[0]);
                } else {
                    $this->render_ajax('error', "Can't save Character");
                }
            }
        } else {
            $this->render_ajax('error', 'Characters not found!');
        }
    }
    
    function recover($params){
        $char = Character::find('first',array('conditions' => array('guid' => $params['id'], 'realm_id' => $params['rid'])));
        if($char){
            if($char->recover()){
                $this->flash('success','Successfully recoverd');
            } else {
                $this->flash('error', $char->errors[0]);
            }
        } else {
            $this->flash('error','Char not found');
        }
        $this->redirect_back();
    }
}
