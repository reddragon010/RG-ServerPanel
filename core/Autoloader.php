<?php

class Framework_Autoloader {
    
    static private $loadpaths = array(
        'FRAMEWORK' => array(
            'core' => '/core/',
            '/lib/'
        ),
        'APP' => array(
            'controller' => '/controllers/',
            'model' => '/models/',
            '/viewextentions/'
        )
    );
    
    static public function register() {
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self, 'autoload'));
    }

    static public function autoload($class) {
        $path = self::get_path($class);
        if($path){
            require_once $path;
        } 
    }
    
    static private function get_path($class){
        $classinfos = self::parse_class_name($class);
        if($classinfos['space'] != ''){
            $path = self::get_path_from_namespace($classinfos);
        } else {
            $path = self::get_path_from_classname($classinfos);
        }
        return $path;
    }
    
    static private function get_path_from_classname($infos){
        foreach(self::$loadpaths as $region => $paths){
            $root_path = constant($region . '_ROOT');
            foreach($paths as $path){
                $fullpath = $root_path . $path . $infos['name'];
                if(file_exists($fullpath)){
                    return $fullpath;
                }
            }
        }
        return false;
    }
    
    static private function get_path_from_namespace($info){
        foreach(self::$loadpaths as $region => $paths){
            if(isset($paths[$info['space']])){
                $path = constant($region . '_ROOT') . $paths[$info['space']] . $info['name'];
                if(file_exists($path)){
                    return $path;
                }
            }
        }
        return false;
    }
    
    static private function parse_class_name($class){
        $pos = strrpos($class, "\\");
        if($pos == true){ 
            $name = substr($class, $pos+1);
            $space = substr($class, 0, $pos);
        } else {
            $name = $class;
            $space = '';
        }
        return array(
            'name' => self::relative_class_path($name),
            'space' => strtolower($space)
        );
    }
    
    static private function relative_class_path($class){
        return str_replace('_','/',$class) . '.php';
    }

}