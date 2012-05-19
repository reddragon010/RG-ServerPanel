<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.05.12
 * Time: 02:39
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Framework\Core;

class Cache extends \Dreamblaze\Cache\CacheManager
{
    private static  $registered = false;

    public static function init(){
        parent::register_stores(array(
            'yaml' => new \Dreamblaze\Cache\JsonFileStore(ROOT . '/cache/yaml'),
            'queries' => new \Dreamblaze\Cache\MemoryStore()
        ));
        self::$registered = true;
    }

    public static function get($name){
        if(!self::$registered)
            self::init();

        return parent::get($name);
    }
}
