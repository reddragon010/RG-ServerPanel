<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file
 *
 * @author mriedmann
 */
class PrivateFile {
    const BASE_PATH = '/private/files/';
    
    private $path;
    private $name;
    
    public function __construct($name){
        $this->name = $name;
        $this->path = FRAMEWORK_ROOT . self::BASE_PATH . $name;
        if(!file_exists($this->path))
            throw new Exception ("File $name under {$this->path} not found!");    
    }
    
    public function get(){
        return file_get_contents( $this->path);
    }
    
    public function put($content){
        return file_put_contents($this->path, $content);
    }
    
    public function age(){
        return filemtime($this->path);
    }
}
