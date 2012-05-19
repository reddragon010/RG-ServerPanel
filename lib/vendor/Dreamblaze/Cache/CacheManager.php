<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.05.12
 * Time: 01:57
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Cache;

class CacheManager
{
    private static $stores = array();


    /**
     * @static
     * @param $name Name of a registered Store
     * @return Store
     * @throws \Exception
     */
    public static function get($name){
        if(isset(self::$stores[$name]))
            return self::$stores[$name];
        else
            throw new \Exception("Cache '$name' is not available");
    }

    public static function register_store($name, Store $store){
        self::$stores[$name] = $store;
    }

    public static function register_stores(array $stores){
        foreach($stores as $name=>$store){
            self::register_store($name, $store);
        }
    }
}
