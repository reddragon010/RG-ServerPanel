<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.05.12
 * Time: 02:04
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Cache;

class FileStore implements Store
{
    private $basedir;

    public function __construct($basedir){
        $this->basedir = $basedir;
    }

    public function write($key, $obj)
    {
        try{
            file_put_contents($this->get_path($key), serialize($obj));
        } catch(\Exception $e){
            throw CacheException("Can't write Cache with key '$key'", $e);
        }
    }

    public function read($key)
    {
        if($this->exists($key)){
            try{
                return unserialize(file_get_contents($this->get_path($key)));
            } catch(\Exception $e){
                throw CacheException("Can't read Cache with key '$key'", $e);
            }
        } else {
            throw CacheException("Can't find Cache with key '$key'");
        }
    }

    public function exists($key){
        return file_exists($this->get_path($key));
    }

    private function get_path($key){
        return $this->basedir . DIRECTORY_SEPARATOR . $key . '.cache';
    }
}
