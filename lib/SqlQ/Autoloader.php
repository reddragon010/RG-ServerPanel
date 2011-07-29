<?php
class SqlQ_Autoloader {

    static public function register() {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    static public function autoload($class) {
        if (substr($class, 0, 4) == 'SqlQ') {
            $name = substr($class, 4);
            $file = dirname(__FILE__) . '/sqlq_' . $name . '.php';
            if (file_exists($file)) {
                require $file;
            }
        }
    }

}
