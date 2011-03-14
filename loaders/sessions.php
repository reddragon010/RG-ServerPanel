<?php
session_start();
$user = null;
if(!empty($_SESSION['userid'])){
	if(!empty($_SESSION['userdata'])){
		$user = User::build($_SESSION['userdata']);
	} else {
		$user = User::find($_SESSION['userid']);
        $_SESSION['userdata'] = $user->_data;
	}
}
