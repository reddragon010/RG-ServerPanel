<?php
/**
* 
*/
class Controller
{	
	function __construct()
	{
	}
	
	public function render($data=array()){
		global $request;
		$tpl = new Template(static::name());
		$tpl->render($request['action'], $data);
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
		$root_url = Environment::$app_url;
		header("Location: /$controller/$action");
	}
	
	function flash($type, $message, $hops=0){
		$_SESSION['flash'] = array();
		$_SESSION['flash']['msg'] = $message;
		$_SESSION['flash']['type'] = $type;
		$_SESSION['flash']['hops'] = $hops;
	}
}
