<?php
/**
* 
*/
class Controller
{
	private $tpl_engine;
	private $tpl_extention;
	private $tpl;
	
	function __construct()
	{
		global $config;
		require_once $config['server_root'].'/lib/Twig/Autoloader.php';
		Twig_Autoloader::register();

		//-- Loading Template Files
		$loader = new Twig_Loader_Filesystem(array(
			$config['server_root']."/themes/{$config['theme']}/views/",
			$config['server_root']."/themes/{$config['theme']}/views/".static::name(),
			$config['server_root']."/themes/{$config['theme']}/views/mails",
		));

		//-- Setting Template-System Config
		if($config['cache']) {$cache = $config['server_root'] . '/cache/views';} else {$cache = false;}
		$this->tpl_engine = new Twig_Environment($loader, array(
		  'cache' => $cache,
			'debug' => $config['debug'],
		));
		
		//-- Load Extentions (Globals, Filter, Functions)
		$ext_name = ucfirst($config['theme']) . 'TemplateExtention';
		require_once($config['server_root']."/themes/{$config['theme']}/extentions/template.php");
		$this->tpl_extention = new $ext_name(&$this->tpl_engine);
	}
	
	public static function name(){
		$class = get_called_class();
		$exploded_class_name = explode('_',$class);
		return $exploded_class_name[0];
	}
	
	function render($data=array()){
		global $request;
		$this->tpl = $this->tpl_engine->loadTemplate($request['action'].'.tpl');
		echo $this->tpl->render($data);
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
