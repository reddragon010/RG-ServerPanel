<?php
//---------------------------------------------------------------------------
//-- Mail Helpers
//---------------------------------------------------------------------------
function send_mail($tpl, $to, $subject, $data){
	global $config, $twig;
	$header = 'From: ' . $config['mail']['from'] . "\r\n" .
	    			'Reply-To: ' . $config['mail']['reply'] . "\r\n" .
	    			'X-Mailer: PHP/' . phpversion();
	$tpl = $twig->loadTemplate($tpl.'.mail.tpl');
	$text = $tpl->render($data);
	return mail($to,$subject,$text,$header);
}