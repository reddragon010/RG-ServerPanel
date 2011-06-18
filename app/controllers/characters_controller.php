<?php
class CharactersController extends BaseController {

    var $before = array(
        'check_login'
    );

    function index($params=array()) {
        $realms = Realm::find('all');
        $chars = array();
        foreach($realms as $realm){
            $chars += $realm->find_characters('all', array('conditions' => $params));
        }
        
        $this->render(array(
            'characters' => $chars,
        ));
    }
    
    function show($params){
        $realm = Realm::find($params['rid']);
        $char = $realm->find_characters('first',array('conditions' => array('guid = ?',$params['id'])));
        $this->render(array('character' => $char));
    }
}
