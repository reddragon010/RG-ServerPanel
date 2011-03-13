<?php
//---------------------------------------------------------------------------
//-- Database Configuration
//---------------------------------------------------------------------------

//-- Website 
$config['db']['web'] = "mysql://root:root@127.0.0.1/mangos_website_test";
//-- Realmd
$config['db']['login'] = "mysql://salja:Schweiz10@78.46.85.239/realmd";
//-- Realms (index have to be equal to realmlist-id)
$config['db']['realm'][1] = "mysql://salja:Schweiz10@78.46.85.239/characters";
//$config['db']['realm'][2] = "mysql://root:root@127.0.0.1/characters";

//---------------------------------------------------------------------------
//-- Paths
//---------------------------------------------------------------------------
$config['page_base'] 	= "/mangos_web_test"; // Sub-Directory of your host e.g.: http://www.example.com/userpanel -> /userpanel
$config['page_host'] 	= "http://salja.dyndns.org/robigo"; // Host-URL 
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
