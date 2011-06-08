<?php

/**
 * 
 */
class Template {
    
    static private $instances = array();
    
    private $name;
    private $theme_name;
    private $tpl_engine;
    private $extentions;
    
    public static function getInstance($name){
        if(!isset(self::$instances[$name])){
            self::$instances[$name] = new Template($name);
        }
        return self::$instances[$name];
    }
    
    private function __construct($name) {
        $this->theme_name = Environment::get_config_value('theme');
        $this->name = $name;

        $tpl_engine_opts = array(
            'loader' => array(
                APP_ROOT . "/themes/{$this->theme_name}/views/",
                APP_ROOT . "/themes/{$this->theme_name}/views/" . $this->name,
                APP_ROOT . "/themes/{$this->theme_name}/mails"),
            'cache' => Environment::get_config_value('cache'),
            'debug' => Environment::get_config_value('debug')
        );

        $this->tpl_engine = new TemplateEngine($tpl_engine_opts);

        $this->load_extentions();
        $this->register_extentions();
    }

    public function render($action, $data=array()) {
        echo $this->tpl_engine->get_rendered_template($action . '.tpl.html', $data);
    }

    private function load_extentions() {
        $this->extentions = array(
            'global' => new tplglobals(),
            'filter' => new tplfilters(),
            'function' => new tplfunctions()
        );
    }

    private function register_extentions() {
        foreach ($this->extentions as $register_name => $class) {
            $methods = get_class_methods($class);
            foreach ($methods as $method_name) {
                $options = array();
                if (strpos($method_name, '_html') == true) {
                    $options['is_safe'][] = 'html';
                    $name = str_replace('_html', '', $method_name);
                }  else {
                    $name = $method_name;
                }
                $this->tpl_engine->{'register_' . $register_name}($class, $name, $method_name, $options);
            }
        }
    }

}
