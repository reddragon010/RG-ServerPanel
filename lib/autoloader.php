<?php
class Autoloader
{
  static public function register() {
	    ini_set('unserialize_callback_func', 'spl_autoload_call');
	    spl_autoload_register(array(new self, 'autoload'));
	}

	static public function autoload($class) {
		global $config;
		$classname = strtolower($class);
		if (file_exists(SERVER_ROOT . '/lib/'. $classname . '.class.php')) {
			require_once(SERVER_ROOT . '/lib/'. $classname . '.class.php');
		} elseif (file_exists(SERVER_ROOT .'/controllers/' . $classname . '.php')) {
			require_once(SERVER_ROOT . '/controllers/' . $classname . '.php');
		} elseif (file_exists(SERVER_ROOT . '/models/' . $classname . '.php')) {
			require_once(SERVER_ROOT . '/models/' . $classname . '.php');
		}
	}
}