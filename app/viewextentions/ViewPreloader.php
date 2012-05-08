<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 08.05.12
 * Time: 03:00
 * To change this template use File | Settings | File Templates.
 */
class ViewPreloader
{
    public static function pre_load(Twig_Environment $twig_env){
        $twig_env->addExtension(new ViewFilters());
        $twig_env->addExtension(new ViewFunctions());
        $twig_env->addExtension(new ViewGlobals());
        return $twig_env;
    }
}
