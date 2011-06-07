<?php

/**
 * 
 */
class Template {

    private $name;
    private $theme_name;
    private $tpl_engine;
    private $extentions;

    public function __construct($name) {
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
        echo $this->tpl_engine->get_rendered_template($action . '.tpl', $data);
    }

    private function load_extentions() {
        $extention_class_names = array('globals' => 'global', 'filters' => 'filter', 'functions' => 'function');

        foreach ($extention_class_names as $extention_name => $register_name) {
            $extention_class_name = $this->theme_name . '_' . $extention_name;
            $this->extentions[$extention_name]['register_name'] = $register_name;
            $this->extentions[$extention_name]['class'] = new $extention_class_name();
        }
    }

    private function register_extentions() {
        foreach ($this->extentions as $name => $content) {
            $methods = get_class_methods($content['class']);
            foreach ($methods as $method_name) {
                if (strpos($method_name, '_html') == true) {
                    $options['is_safe'] = array('html');
                    $name = str_replace('_html', '', $method_name);
                    $this->tpl_engine->{'register_' . $content['register_name']}($content['class'], $name, $method_name, $options);
                } else {
                    $this->tpl_engine->{'register_' . $content['register_name']}($content['class'], $method_name, $method_name);
                }
            }
        }
    }

}
