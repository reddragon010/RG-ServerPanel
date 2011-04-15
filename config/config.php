<?php
//---------------------------------------------------------------------------
//-- Database Configuration
//---------------------------------------------------------------------------

//-- Website 
$config['db']['web'] = "mysql://root@127.0.0.1/tc_website";
//-- Realmd
$config['db']['login'] = "mysql://root@127.0.0.1/auth?charset=utf8";
//-- Realms (index have to be equal to realmlist-id)
$config['db']['realm'][1] = "mysql://root@127.0.0.1/characters?charset=utf8";
//$config['db']['realm'][2] = "mysql://root:root@127.0.0.1/characters";

//---------------------------------------------------------------------------
//-- Paths
//---------------------------------------------------------------------------
$config['page_base'] 	= "/serverpanel"; // Sub-Directory of your host e.g.: http://www.example.com/userpanel -> /userpanel
$config['page_host'] 	= "http://localhost/~mriedmann"; // Host-URL 
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
