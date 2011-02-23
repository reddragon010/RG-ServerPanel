<?php
session_start();
if(!isset($user))
	$user = new User;
	
if($user->logged_in())
	$user->fetchMainChar();