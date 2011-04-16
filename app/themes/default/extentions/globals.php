<?php
class default_globals {
	//---------------------------------------------------------------------------
	//-- Globals
	//---------------------------------------------------------------------------	

	function tpl_global_user(){
		global $user;
		return $user;
	}

	function tpl_global_realms(){
		global $config;
		$realms = array();
		foreach($config['db']['realm'] as $key => $value){
			$realms[] = Realm::find($key);
		}
		return $realms;
	}

	function tpl_global_STATUS(){
		global $STATUS;
		return $STATUS;
	}

	function tpl_global_rooturl(){
		global $config;
		return $config['page_root'];
	}

	function tpl_global_themeurl(){
		global $config;
		return $config['page_root'] . '/themes/' . $config['theme'];
	}

	function tpl_global_TICKETSTATUS(){
		global $TICKET_STATUS;
		return $TICKET_STATUS;
	}
}