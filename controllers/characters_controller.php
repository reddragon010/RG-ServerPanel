<?php
/**
* 
*/
class characters_controller extends Controller
{
	
	function index($params=array()){
		global $realms;
		if(isset($params['realm'])){
			$realm = $realms[$params['realm']]; 
		} else {
			$realm = $realms[1]; 
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
			$chars = $realm->get_online_chars('`'.protect($params['sort']).'` '.$sort_order);
		} else {
			$chars = $realm->get_online_chars();
		}

		$chars_count = $realm->get_online_chars_count();
		$chars_ally_count = $realm->get_online_ally_chars_count();
		$chars_horde_count = $realm->get_online_horde_chars_count();
		$gms = $realm->get_online_gm_chars();
		$gms_count = count($gms);

		$this->render('chars_online.tpl', array(
			'chars' => $chars, 
			'chars_count' => $chars_count, 
			'ally_count' => $chars_ally_count, 
			'horde_count' => $chars_horde_count,
			'gms' => $gms,
			'gms_count' => $gms_count,
			'realm_id' => $realm->id,
			'sort_order' => $new_sort_order
		));
	}
}
