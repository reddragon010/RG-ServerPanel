<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.05.12
 * Time: 03:20
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Cache;

class JsonFileStore implements Store
{
    private $basedir;

    public function __construct($basedir){
        $this->basedir = $basedir;
    }

    public function write($key, $obj)
    {
        try{
            file_put_contents($this->get_path($key), json_encode($obj));
        } catch(\Exception $e){
            throw new CacheException("Can't write Cache with key '$key'", $e);
        }
    }

    public function read($key)
    {
        if($this->exists($key)){
            try{
                return json_decode(file_get_contents($this->get_path($key)), true);
            } catch(\Exception $e){
                throw new CacheException("Can't read Cache with key '$key'", $e);
            }
        } else {
            throw new CacheException("Can't find Cache with key '$key'");
        }
    }

    public function exists($key){
        return file_exists($this->get_path($key));
    }

    private function get_path($key){
        return $this->basedir . DIRECTORY_SEPARATOR . $key . '.json';
    }

}
