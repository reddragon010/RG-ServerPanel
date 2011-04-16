<?php
/**
* 
*/
class characters_controller extends Controller
{
	
	function index($params=array()){
		global $ALLIANCE, $HORDE;
		
		if(isset($params['realm'])){
			$realm = Realm::find($params['realm']); 
		} else {
			$realm = Realm::find(1); 
		}
		
		if(isset($params['order'])){
			$sort_order = protect($params['order']);
			if($sort_order == 'ASC'){
				$new_sort_order = 'DESC';
			} else {
				$new_sort_order = 'ASC';
			}
		} else {
			$sort_order = 'ASC';
			$new_sort_order = 'ASC';
		}

		if(isset($params['sort'])){
			$chars = $realm->find_characters('all',array('conditions' => array(''), 'sort' => protect($params['sort']).' '.$sort_order));
		} else {
			$chars = $realm->find_characters('all',array('conditions' => array("online = '1'")));
		}

		$chars_count = $realm->find_characters_count(array('conditions' => array("online = '1'")));
		$chars_ally_count = $realm->find_characters_count(array('conditions' => array("online = '1'", "`race` IN (".implode(',' , $ALLIANCE).")")));
		$chars_horde_count = $realm->find_characters_count(array('conditions' => array("online = '1'", "`race` IN (".implode(',' , $HORDE).")")));
		$gms = array_filter($chars, function($char){
            return $char->user->is_gm();
        });
		$gms_count = count($gms);
		$tpl_data = array(
			'chars' => $chars, 
			'chars_count' => $chars_count, 
			'ally_count' => $chars_ally_count, 
			'horde_count' => $chars_horde_count,
			'gms' => $gms,
			'gms_count' => $gms_count,
			'realm' => $realm,
			'sort_order' => $new_sort_order
		);
		$this->render($tpl_data);
	}
}
