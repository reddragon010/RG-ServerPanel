<?php

function send_mail($tpl, $to, $subject, $data){
	global $config;
	$header = 'From: ' . $config['mail']['from'] . "\r\n" .
	    			'Reply-To: ' . $config['mail']['reply'] . "\r\n" .
	    			'X-Mailer: PHP/' . phpversion();
	$text		= tpl_load(file_get_contents('mail/' . $tpl . '.php'),$data);
	return mail($to,$subject,$text,$header);
}

function tpl_load($text, $data){
	foreach($data as $k => $v){
		$text = str_replace("%{$k}%",$v,$text);
	}
	return $text;
}

?>