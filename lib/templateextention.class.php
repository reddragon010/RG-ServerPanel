<?php
/**
* 
*/
class TemplateExtention
{
	var $class;
	var $tpl_engine;
	
	function __construct(&$tpl_engine)
	{
		$this->class = get_called_class();
		$this->methods = get_class_methods($this->class);
		$this->tpl_engine = &$tpl_engine;
		
		foreach($this->methods as $method_name){
			$exploded_method_name = explode('_', $method_name);
			$html = isset($exploded_method_name[3]) && $exploded_method_name[3] == 'html';
			if($exploded_method_name[0] == 'tpl'){
				switch($exploded_method_name[1]){
				case 'function':
					$this->register_function($exploded_method_name[2], $method_name,  $html);
					break;
				case 'filter':
					$this->register_filter($exploded_method_name[2], $method_name, $html);
					break;
				case 'global':
					$value = call_user_func(array($this->class,$method_name));
					$this->register_global($exploded_method_name[2],$value);
				}
			}
		}
	}
	
	function register_function($name, $function_name,$html=false){
		$options = array();
		if($html){
			$options['is_safe'] = array('html');
		}
		$this->tpl_engine->addFunction($name,	new Twig_Function_Function($this->class.'::'.$function_name, $options));
	}
	
	function register_filter($name, $function_name,$html=false){
		$options = array();
		if($html){
			$options['is_safe'] = array('html');
		}
		$this->tpl_engine->addFilter($name, new Twig_Filter_Function($this->class.'::'.$function_name, $options));
	}
	
	function register_global($name, $value){
		$this->tpl_engine->addGlobal($name, $value);
	}
}
