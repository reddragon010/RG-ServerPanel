<?php
/**
* 
*/
class Borg
{
	private static $vars = array();
	
	public function __get($key){
		if(!isset(self::$vars[$key]))
			throw new Exception("invalid key");
		
		return self::$vars[$key]; 
	}
	
	public function __set($key, $value){
		self::$vars[$key] = $value;
	}
	
}
