<?php
/**
* 
*/
class Controller
{
	
	function __construct()
	{
		
	}
	
	function render($tpl,$data=array()){
		global $twig;
		$tpl = $twig->loadTemplate($tpl);
		echo $tpl->render($data);
	}
	
	function render_ajax($status,$msg=""){
		$return['status'] = $status;
		$return['msg'] = $msg;
		echo json_encode($return);
	}
	
	function redirect_to($controller,$action){
		global $config;
		header("Location: {$config['page_root']}/$controller/$action");
	}
	
	function flash($type, $message, $hops=0){
		$_SESSION['flash'] = array();
		$_SESSION['flash']['msg'] = $message;
		$_SESSION['flash']['type'] = $type;
		$_SESSION['flash']['hops'] = $hops;
	}
}
