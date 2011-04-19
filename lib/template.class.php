<?php
/**
* 
*/
class Template
{
	private $name;
	private $theme_name;
	private $tpl_engine;
	
	public function __construct($name){
		global $config;
		
		$this->theme_name = $config['theme'];
		$this->name = $name;
		
		$tpl_engine_opts = array(
			'loader' => array(
				APP_ROOT."/themes/{$this->theme_name}/views/",
				APP_ROOT."/themes/{$this->theme_name}/views/".$this->name,
				APP_ROOT."/themes/{$this->theme_name}/mails"),
			'cache' => $config['cache'],
			'debug' => $config['debug']
		);
		
		$this->tpl_engine = new TemplateEngine($tpl_engine_opts);
		$this->load_extentions();
	}
	
	public function render($action, $data=array()){
		echo $this->tpl_engine->get_rendered_template($action.'.tpl', $data);
	}
	
	private function load_extentions(){
		$extentions['globals_class'] = new $this->name . '_globals';
		$extentions['filters_class'] = new $this->name . '_filters';
		$extentions['functions_class'] = new $this->name . '_functions';
		
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
