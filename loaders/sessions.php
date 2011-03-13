<?php
session_start();
if(!empty($_SESSION['userid'])){
	if(!empty($_SESSION['user'])){
		$user = $_SESSION['user'];
	} else {
		$user = User::find($_SESSION['userid']);
	}
}
	
