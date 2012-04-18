<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.04.12
 * Time: 01:29
 * To change this template use File | Settings | File Templates.
 */
class GenericLogger_Autoloader
{
    static public function register() {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    static public function autoload($class) {
        if (substr($class, 0, 13) == 'GenericLogger') {
            $file = self::name_to_path($class);
            if (file_exists($file)) {
                require $file;
            }
        }
    }

    private static function name_to_path($class){
        if($class == "GenericLogger") return dirname(__FILE__) . '/' . 'logger.php';
        $name = substr($class, 14);
        $lc_name = strtolower($name);
        return dirname(__FILE__) . '/' . $lc_name . '.php';
    }
}
