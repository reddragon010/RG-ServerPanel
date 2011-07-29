<?php
class TemplateEngine extends Singleton {

    private $opts;
    private $twig;

    protected function __construct() {
        
    }
    
    public function set_opts($opts){
        $this->opts = $opts;
    }

    public function get_rendered_template($template, $data=array()) {
        $template = $this->twig->loadTemplate($template);
        return $template->render($data);
    }

    public function register_function($class, $function_name, $method_name, $options=array()) {
        $this->twig->addFunction($function_name, new Twig_Function_Function('\\' . get_class($class) . '::' . $method_name, $options));
    }

    public function register_filter($class, $filter_name, $method_name, $options=array()) {
        $this->twig->addFilter($filter_name, new Twig_Filter_Function('\\' . get_class($class) . '::' . $method_name, $options));
    }

    public function register_global($class, $global_name, $method_name, $options=array()) {
        $value = call_user_func('\\' . get_class($class) . '::' . $method_name);
        $this->twig->addGlobal($global_name, $value);
    }

    public function load() {
        Twig_Autoloader::register();

        $loader = $this->get_loader();
        $config = $this->get_config();

        $this->twig = new Twig_Environment($loader, $config);
    }

    private function get_config() {
        if ($this->opts['cache']) {
            $cache = APP_ROOT . '/cache/views';
        } else {
            $cache = false;
        }

        $config = array(
            'cache' => $cache,
            'debug' => $this->opts['debug']
        );
        return $config;
    }

    private function get_loader() {
        return new \Twig_Loader_Filesystem($this->opts['loader']);
    }

}
