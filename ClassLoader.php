<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 08.05.12
 * Time: 00:33
 * To change this template use File | Settings | File Templates.
 */
use \Symfony\Component\ClassLoader\UniversalClassLoader;

class ClassLoader extends UniversalClassLoader
{
    public static $instance;

    public static function startup(){
        self::$instance = new ClassLoader();

        // --- Contrib Namespaced Classes/Libs
        self::$instance->registerNamespaces(array(
            'Symfony' => LIB_ROOT . '/vendor',
            'Dreamblaze' => LIB_ROOT . '/vendor',
        ));

        // --- Contrib Prefixed Classes/Libs
        self::$instance->registerPrefixes(array(
            'Twig_' => LIB_ROOT . '/vendor',
            'FB' => LIB_ROOT . '/FirePHPCore'
        ));

        self::$instance->registerPrefixFallbacks(array(
            APP_ROOT . '/controllers',
            APP_ROOT . '/models',
            APP_ROOT . '/viewextentions',
        ));

        self::$instance->register();
    }
}
