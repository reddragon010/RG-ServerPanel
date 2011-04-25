<?php
class default_globals {
	function user(){
		global $user;
		return $user;
	}

	function realms(){
		global $config;
		$realms = array();
		foreach($config['db']['realm'] as $key => $value){
			$realms[] = Realm::find($key);
		}
		return $realms;
	}

	function STATUS(){
		global $STATUS;
		return $STATUS;
	}

	function rooturl(){
		return APP_ROOT;
	}

	function themeurl(){
		global $config;
		return APP_URL . '/app/themes/' . $config['theme'];
	}

	function TICKETSTATUS(){
		global $TICKET_STATUS;
		return $TICKET_STATUS;
	}
}