<?php
class application_controller
{
	function on_each_request(){
		$this->load_user();
	}
	
	function load_user(){
		global $user;
		if(!isset($user) && !empty($_SESSION['userid'])){
			if(!empty($_SESSION['userdata'])){
				$user = User::build($_SESSION['userdata']);
			} else {
				$user = User::find($_SESSION['userid']);
		        $_SESSION['userdata'] = $user->data;
			}
		}
	}
}
