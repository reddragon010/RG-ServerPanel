<?php
$db_host="localhost"; //Database Host
$accdb_name="realmd"; //Account DB
$db_username="mangos"; //Database Connect Username
$db_password="mangos"; //Database Connect Password

$server_realmlist="78.46.85.239"; //Your Server Realmlist
//Do U Not Edit This Part If You Don't Know what you are doing!!!
$db_con=mysql_connect($db_host,$db_username,$db_password);
$connection_string=mysql_select_db($accdb_name);
mysql_connect($db_host,$db_username,$db_password);
mysql_select_db($accdb_name);


 
$ip = "127.0.0.1";
$port = "3306";
$host = "localhost";
$user = "mangos";
$pass = "mangos"; 
$mangoscharacters = "characters";
$mangosrealm = "realmd";
$website = "website";
$cod = 'utf8';
?>