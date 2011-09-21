<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of StupidPrlf.
 *
 *    StupidPrlf is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    StupidPrlf is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with StupidPrlf.  If not, see <http://www.gnu.org/licenses/>.
 */

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
