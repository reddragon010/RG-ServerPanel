<?php
class CharactersController extends BaseController {

    var $before = array(
        'check_login'
    );

    function index($params=array()) {
        global $ALLIANCE, $HORDE;

        $realms = Realm::find('all');
        $chars = array();
        
        foreach($realms as $realm){
            $chars += $realm->find_characters('all');
        }

        $tpl_data = array(
            'characters' => $chars,
        );
        $this->render($tpl_data);
    }

}
