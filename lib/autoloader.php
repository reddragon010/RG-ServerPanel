<?php
class Autoloader
{
  static public function register() {
	    ini_set('unserialize_callback_func', 'spl_autoload_call');
	    spl_autoload_register(array(new self, 'autoload'));
	}

	static public function autoload($class) {
		$classname = strtolower($class);
		if (file_exists(FRAMEWORK_ROOT . '/lib/'. $classname . '.class.php')) {
			require_once(FRAMEWORK_ROOT . '/lib/'. $classname . '.class.php');
		} elseif (file_exists(APP_ROOT .'/controllers/' . $classname . '.php')) {
			require_once(APP_ROOT . '/controllers/' . $classname . '.php');
		} elseif (file_exists(APP_ROOT . '/models/' . $classname . '.php')) {
			require_once(APP_ROOT . '/models/' . $classname . '.php');
		} elseif (file_exists($file = FRAMEWORK_ROOT.'/lib/'.str_replace('_', '/', $class).'.php')){
			require $file;
		} else {
			$expl_classname = explode('_', $classname);
			if(file_exists(APP_ROOT . '/themes/' . $expl_classname[0] . '/extentions/' . $expl_classname[1] . '.php')){
				require_once(APP_ROOT . '/themes/' . $expl_classname[0] . '/extentions/' . $expl_classname[1] . '.php');
			} else {
				throw new Exception("Class $class Not Found!");
			}
		}
	}
}