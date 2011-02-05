<?php
//---------------------------------------------------------------------------
//-- Database Server
//---------------------------------------------------------------------------
$config['db']['host']     = "127.0.0.1:3306"; // Database Host
$config['db']['username'] = "root"; // Database Connect Username
$config['db']['password'] = ""; // Database Connect Password

$config['db']['realmdb'] = "s_realmd"; // AccountDatabase
$config['db']['chardb']  = "s_characters"; // CharactersDatabase
$config['db']['webdb']   = "s_website";	// WebsiteDatabase

//---------------------------------------------------------------------------
//-- Mangos Server
//---------------------------------------------------------------------------
//-- Login Server
$config['login']['ip'] = "127.0.0.1";
$config['login']['port'] = "1000";

//-- Realm Server with ID 1
$config['realms'][1]['ip']   = "127.0.0.1";
$config['realms'][1]['port'] = "3306";
$config['realms'][1]['name'] = "Debug";

//-- Realm Server with ID 2
//$config['realms'][2]['ip']   = "127.0.0.1";
//$config['realms'][2]['port'] = "3306";
//$config['realms'][2]['name'] = "Debug";

//---------------------------------------------------------------------------
//-- Paths
//---------------------------------------------------------------------------
$config['root_base'] 	= "/website"; // Sub-Directory of your host e.g.: http://www.example.com/userpanel -> /userpanel
$config['root_host'] 	= "http://localhost:10088"; // Host-URL 
$config['root_path'] 	= "/Users/mriedmann/Projects/salja/Website"; // Absolut path

//---------------------------------------------------------------------------
//-- Mail
//---------------------------------------------------------------------------
$config['mail']['from']		= "no-reply@salja.com"; // Address for outgoing mails
$config['mail']['reply'] 	= "webmaster@salja.com"; // Address for answers

//---------------------------------------------------------------------------
//-- Misc
//---------------------------------------------------------------------------
$config['cache'] = false;
$config['debug'] = true;

?>