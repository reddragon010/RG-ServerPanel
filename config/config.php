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
$config['web']['db'] = "s_website";

//-- Login-Server
$config['login']['host'] = "127.0.0.1";
$config['login']['port'] = "1000";
$config['login']['db_port'] = "3306";
$config['login']['db_username'] = "root";
$config['login']['db_password'] = "root";
$config['login']['db'] = "s_realmd";

//-- Realm-Server with ID 1
$config['realm'][1]['host'] = "127.0.0.1";
$config['realm'][1]['port'] = "3306";
$config['realm'][1]['name'] = "Debug";
$config['realm'][1]['db_port'] = "3306";
$config['realm'][1]['db_username'] = "root";
$config['realm'][1]['db_password'] = "root";
$config['realm'][1]['db'] = "s_characters";

//-- Realm-Server with ID 2
$config['realm'][2]['host']   = "127.0.0.1";
$config['realm'][2]['port'] = "3306";
$config['realm'][2]['name'] = "Debug2";
$config['realm'][2]['db_port'] = "3306";
$config['realm'][2]['db_username'] = "root";
$config['realm'][2]['db_password'] = "root";
$config['realm'][2]['db'] = "s_characters";

//---------------------------------------------------------------------------
//-- Paths
//---------------------------------------------------------------------------
$config['page_base'] 	= "/website"; // Sub-Directory of your host e.g.: http://www.example.com/userpanel -> /userpanel
$config['page_host'] 	= "http://localhost:10088"; // Host-URL 
$config['server_root'] 	= "/Users/mriedmann/Projects/salja/Website"; // Absolut path
$config['page_root']		= $config['page_host'] . $config['page_base'];

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
$config['registration'] = true;

?>