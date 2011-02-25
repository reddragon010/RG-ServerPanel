<?php
/**
* 
*/
class site_controller extends Controller
{
	var $db = NULL;
	var $twig = NULL;
	
	function __construct()
	{
		global $config, $twig;
		$this->twig = $twig;
		$this->db = new Database($config['web']); 
	}
	
	function home(){

	}
	
	function howto(){
		$this->render('site_howto.tpl');
	}
	
	function tools(){
		$this->render('tools.tpl');
	}
	
	
}
