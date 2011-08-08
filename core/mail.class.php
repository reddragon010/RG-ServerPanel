<?php

class Mail {

    function __construct() {
        
    }

    function send($tpl, $to, $subject, $data) {
        $mail_config = Environment::get_value('mail');
        $header = 'From: ' . $mail_config['from'] . "\r\n" .
                'Reply-To: ' . $mail_config['reply'] . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
        $tpl = $twig->loadTemplate($tpl . '.mail.tpl');
        $text = $tpl->render($data);
        return mail($to, $subject, $text, $header);
    }

}
