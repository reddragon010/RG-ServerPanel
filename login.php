<?php
session_start(); 

require_once ('config.php');
require_once ('functions.php');

if(isset($_REQUEST['username']) && isset($_REQUEST['password'])){
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	
	$username = protect($_REQUEST['username']);
    $password = protect($_REQUEST['password']);
	
	if($password){
		$password = sha1(strtoupper($username) . ":" . strtoupper($password));	
	}
	
	if($username && $password){
		
	  	$sql = "SELECT id,username,gmlevel,email FROM `account` WHERE `username`='".$username."' AND `sha_pass_hash` = '".$password."'";
	  	$res = mysql_query($sql) or die(mysql_error());
	
		if(mysql_num_rows($res) > 0){
			
			// Benutzerdaten in ein Array auslesen.  
			$data = mysql_fetch_array ($res);  
			
			// Sessionvariablen erstellen und registrieren  
			$_SESSION["id"] 		= $data["id"];  
		 	$_SESSION["username"] 	= $data["username"];  
	  		$_SESSION["gmlevel"] 	= $data["gmlevel"];  
			$_SESSION["email"] 		= $data["email"];  
			
			header ("Location: intern.php");  	
			
		}else{
			//Wenn Username/Passwort nicht in der Datenbank existieren:
			header ("Location: index.php?fehler=1");
		}
	}else{
		header ("Location: index.php?fehler=2");	
	}
	
}else{
	echo "<h2>Unauthorisierter Zugriff!</h2>"; 	
} 
?> 