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
}
