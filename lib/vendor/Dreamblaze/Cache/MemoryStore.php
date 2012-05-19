<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.05.12
 * Time: 02:08
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Cache;

class MemoryStore implements Store
{
    private $store = array();


    public function write($key, $obj)
    {
        $this->store[$key] = $obj;
    }

    public function read($key)
    {
        if($this->exists($key))
            return $this->store[$key];
        else
            throw new CacheException("Can't find Cache with key '$key'");
    }

    public function exists($key){
        return isset($this->store[$key]) && !empty($this->store[$key]);
    }
}
