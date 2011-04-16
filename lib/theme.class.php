<?php
/**
* 
*/
class Theme
{
	var $name;
	var $class;
	
	private static $instance;
	private $tpl_engine;
	
	public function get_instance($theme){
		if(!isset(self::$instance)){
			$c = __CLASS__;
			self::$instance = new $c($theme);
		}
		
		return self::$instance;
	}
	
	private function __construct(){
		global $config;
		
		$this->class = __CLASS__;
		$this->name = str_replace('Theme','',$this->class);
		
		$this->load_tpl_engine();
		$this->load_extentions();
	}
	
	public function render($data=array()){
		global $request;
		$this->tpl = $this->tpl_engine->loadTemplate($request['action'].'.tpl');
		echo $this->tpl->render($data);
	}
	
	private function load_tpl_engine(){
		global $config;
		Twig_Autoloader::register();

		//-- Loading Template-Engine Filessysten
		$loader = new Twig_Loader_Filesystem(array(
			APP_ROOT."/themes/{$this->name}/views/",
			APP_ROOT."/themes/{$this->name}/views/".static::name(),
			APP_ROOT."/themes/{$this->name}/mails",
		));

		//-- Setting Template-Engine Config
		if($config['cache']) {$cache = APP_ROOT . '/cache/views';} else {$cache = false;}
		$tpl_config = array(
		  'cache' => $cache,
			'debug' => $config['debug'],
		);
		
		//-- Load Template-Engine
		$this->tpl_engine = new Twig_Environment($loader, $tpl_config);
	}
	
	private function load_extentions(){
		$extentions['globals_class'] = new $this->class . '_globals';
		$extentions['filters_class'] = new $this->class . '_filters';
		$extentions['functions_class'] = new $this->class . '_functions';
		
		foreach($extentions as $ext){
			$ext_methods = get_class_methods($ext);
			foreach($this->methods as $method_name){
				$this->register_extention($method_name);
			}
		}
	}
	
	private function register_extention($method_name){
		
		if(strpos($method_name,'_html')===true){
			$html = true;
			$method_name = str_replace('_html', '', $method_name);
		} else {
			$html = false;
		}
		
		$this->{'register_'.$method_name}($ext, $method_name, $html);
	}
	
	private function register_function($class, $function_name, $html){
		$options = array();
		if($html){
			$options['is_safe'] = array('html');
		}
		$this->tpl_engine->addFunction($function_name,	new Twig_Function_Function($class.'::'.$function_name, $options));
	}
	
	private function register_filter($class, $function_name, $html){
		$options = array();
		if($html){
			$options['is_safe'] = array('html');
		}
		$this->tpl_engine->addFilter($expl_name[2], new Twig_Filter_Function($class.'::'.$function_name, $options));
	}
	
	private function register_global($class, $function_name, $html){
		$value = call_user_func(array($class,$function_name));
		$this->tpl_engine->addGlobal($expl_name[2], $value);
	}

}
