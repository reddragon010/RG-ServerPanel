<?php

class PHP_Debug_Autoloader {

    static public function register() {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    static public function autoload($class) {
        if (substr($class, 0, 3) == 'PHP') {
            $file = dirname(__FILE__) . '/' . str_replace(array('_', "\0"), array('/', ''), $class) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        }
    }

}