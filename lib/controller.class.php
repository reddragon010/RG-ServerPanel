<?php
/**
* 
*/
class Controller
{
	private $theme;
	
	function __construct()
	{
		global $config;
		
		//-- Load Theme
	}
	
	public function render($data=array()){
		$this->theme->render($data);
	}
	
	public function render_ajax($status,$msg=""){
		$return['status'] = $status;
		$return['msg'] = $msg;
		echo json_encode($return);
	}
	
	public static function name(){
		$class = get_called_class();
		$exploded_class_name = explode('_',$class);
		return $exploded_class_name[0];
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
