<?php
class SqlS_Autoloader {

    static public function register() {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    static public function autoload($class) {
        if (substr($class, 0, 4) == 'SqlS') {
            $file = self::name_to_path($class);
            if (file_exists($file)) {
                require $file;
            }
        }
    }
    
    private static function name_to_path($class){
        if($class == "SqlS") return dirname(__FILE__) . '/' . 'sqls.php';
        $name = substr($class, 5);
        $uc_name = self::from_camel_case($name);
        $s_name = explode('_', $uc_name);
        return dirname(__FILE__) . '/' . $s_name[0] . '/' . $s_name[1] . '.php';
    }
    
    private static function from_camel_case($str) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }
}
