<?php
/**
* 
*/
class TemplateEngine
{
	private $opts;
	private $theme_name;
	private $twig;
	
	function __construct($opts=array()){
		$this->opts = $opts;
		$this->load();
	}	
	
	public function get_rendered_template($template, $data=array()){
		$template = $this->twig->loadTemplate($template);
		return $template->render($data);
	}
	
	private function load(){
		Twig_Autoloader::register();

		$loader = $this->get_loader();
		$config = $this->get_config();
		
		$this->twig = new Twig_Environment($loader, $config);
	}
	
	private function get_config(){
		$cache = $this->opts['cache'] ? APP_ROOT . '/cache/views' : false;
		
		$tpl_config = array(
		  'cache' => $cache,
			'debug' => $this->opts['debug']
		);
	}
	
	private function get_loader(){
		$loader = new Twig_Loader_Filesystem($this->opts['loader']);
	}
}
