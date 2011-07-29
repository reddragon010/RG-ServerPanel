<?php
class Template extends SingletonStore {
    private $name;
    private $theme_name;
    private $tpl_engine;
    private $extentions;
    
    protected function __construct($name) {
        $this->theme_name = Environment::get_config_value('theme');
        $this->name = $name;
        
        $this->tpl_engine = TemplateEngine::instance();
        $this->tpl_engine->set_opts(array(
            'loader' => array(
                APP_ROOT . "/views/",
                APP_ROOT . "/views/" . $this->name,
                APP_ROOT . "/mails/"),
            'cache' => Environment::get_config_value('cache'),
            'debug' => Environment::get_config_value('debug')
        ));
        $this->tpl_engine->load();

        $this->load_extentions();
        $this->register_extentions();
    }

    public function render($action, $data=array()) {
        echo $this->tpl_engine->get_rendered_template($action . '.tpl.html', $data);
    }

    private function load_extentions() {
        $this->extentions = array(
            'global' => new \tplglobals(),
            'filter' => new \tplfilters(),
            'function' => new \tplfunctions()
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

