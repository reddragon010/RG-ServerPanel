<?php
//---------------------------------------------------------------------------
//-- Server Configuration
//---------------------------------------------------------------------------
//-- Web-Server
$config['web']['host'] = "127.0.0.1";
$config['web']['port'] = "3306";
$config['web']['db_port'] = "3306";
$config['web']['db_username'] = "root";
$config['web']['db_password'] = "root";
$config['web']['db'] = "mangos_website";

//-- Login-Server
$config['login']['host'] = "127.0.0.1";
$config['login']['port'] = "1000";
$config['login']['db_port'] = "3306";
$config['login']['db_username'] = "root";
$config['login']['db_password'] = "root";
$config['login']['db'] = "realmd";

//-- Realm-Server with ID 1
$config['realm'][1]['host'] = "127.0.0.1";
$config['realm'][1]['port'] = "3306";
$config['realm'][1]['name'] = "Debug";
$config['realm'][1]['db_port'] = "3306";
$config['realm'][1]['db_username'] = "root";
$config['realm'][1]['db_password'] = "root";
$config['realm'][1]['db'] = "characters";

//-- Realm-Server with ID 2
//$config['realm'][2]['host']   = "127.0.0.1";
//$config['realm'][2]['port'] = "3306";
//$config['realm'][2]['name'] = "Debug2";
//$config['realm'][2]['db_port'] = "3306";
//$config['realm'][2]['db_username'] = "root";
//$config['realm'][2]['db_password'] = "";
//$config['realm'][2]['db'] = "characters";

//---------------------------------------------------------------------------
//-- Paths
//---------------------------------------------------------------------------
$config['root_base'] 	= "/account"; // Sub-Directory of your host e.g.: http://www.example.com/userpanel -> /userpanel
$config['root_host'] 	= "http://salja.dyndns.org"; // Host-URL 
$config['root_path'] 	= "/Users/mriedmann/Projects/salja/Website"; // Absolut path
$config['root_url']		= $config['root_host'] . $config['root_base'];

//---------------------------------------------------------------------------
//-- Mail
//---------------------------------------------------------------------------
$config['mail']['from']		= "no-reply@salja.com"; // Address for outgoing mails
$config['mail']['reply'] 	= "webmaster@salja.com"; // Address for answers

//---------------------------------------------------------------------------
//-- RepoTracker
//---------------------------------------------------------------------------
$config['repos'][] = 'http://salja.dyndns.org/gitweb/?p=MaNGOS.git;a=rss;h=refs/heads/Salja';
$config['repos'][] = 'http://salja.dyndns.org/gitweb/?p=ScriptDev2.git;a=rss;h=refs/heads/master_development';

//---------------------------------------------------------------------------
//-- Misc
//---------------------------------------------------------------------------
$config['lang']	 = 'de';
$config['theme'] = 'cata';
$config['cache'] = false;
$config['debug'] = true;
$config['registration'] = false;

?>