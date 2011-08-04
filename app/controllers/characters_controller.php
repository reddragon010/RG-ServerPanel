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
            $chars += $realm->find_characters('all', array('conditions' => $params));
            $chars_count += $realm->find_characters_count(array('conditions' => $params));
        }
        
        $this->render(array(
            'chars_count' => $chars_count,
            'characters' => $chars,
        ));
    }
    
    function show($params){
        $realm = Realm::find($params['rid']);
        $char = $realm->find_characters('first',array('conditions' => array('guid' => $params['id'])));
        $this->render(array('character' => $char));
    }
    
    function recover($params){
        $realm = Realm::find($params['rid']);
        $char = $realm->find_characters('first',array('conditions' => array('guid' => $params['id'])));
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
