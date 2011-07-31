<?php

class Debug {

    private static $engine;
    private static $loaded = false;

    static function setup() {
        if (!self::$loaded) {
            self::load();
            self::$loaded = true;
        }
        if (!isset(self::$engine)) {
            try{
                $opts = Environment::get_config_value('phpdebug');
            } catch(Exception $e) {
                $opts = array(
                    'render_type'          => 'HTML',    // Renderer type
                    'render_mode'          => 'Div',     // Renderer mode
                    'restrict_access'      => false,     // Restrict access of debug
                    'allow_url_access'     => false,      // Allow url access
                    'enable_watch'         => false,     // Enable wath of vars
                    'replace_errorhandler' => true,      // Replace the php error handler
                    'lang'                 => 'EN',      // Lang
 
                    // Renderer specific
                    'HTML_DIV_view_source_script_name' => 'PHP_Debug_ShowSource.php',
                    'HTML_DIV_remove_templates_pattern' => true,
                    'HTML_DIV_templates_pattern' => array('/var/www-protected/php-debug.com' => '/var/www/php-debug'),
                    'HTML_DIV_images_path' => '/images/phpdebug', 
                    'HTML_DIV_css_path' => '/css/phpdebug',
                    'HTML_DIV_js_path' => '/js/phpdebug',
                );
            }
            self::$engine = new PHP_Debug($opts);
        }
    }

    private function __construct() {
        
    }

    static private function load() {
        PHP_Debug_Autoloader::register();
        set_include_path(FRAMEWORK_ROOT . '/lib/PHP_Debug/' . PATH_SEPARATOR . get_include_path());
    }

    static function _query($sql, $values) {
        foreach ($values as $key=>$value) {
            $sql = str_replace($key,$value,$sql);
        }
        //self::$engine->dump($values);
        return self::$engine->query($sql);
    }

    public static function __callStatic($name, $parameters) {
        if (!self::$loaded) {
            return false;
        } else if (method_exists('Debug', '_' . $name)) {
            return call_user_func_array(array('Debug', '_' . $name), $parameters);
        } else if (method_exists(self::$engine, $name)) {
            return call_user_func_array(array(self::$engine, $name), $parameters);
        }
    }

}
