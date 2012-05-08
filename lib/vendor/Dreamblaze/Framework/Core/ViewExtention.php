<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 08.05.12
 * Time: 02:38
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Framework\Core;

class ViewExtention extends \Twig_Extension
{
    public function getName(){
        return get_class($this);
    }

    protected function generateFunctions($names){
        $funcs = array();
        foreach($names as $name)
            $funcs[$name] = new \Twig_Function_Method($this,$name);
        return $funcs;
    }

    protected function generateHtmlFunctions($names){
        $funcs = array();
        foreach($names as $name)
            $funcs[$name] = new \Twig_Function_Method($this,$name,array('is_safe' => array('html')));
        return $funcs;
    }

    protected function generateFilters($names){
        $filters = array();
        foreach($names as $name)
            $filters[$name] = new \Twig_Filter_Method($this,$name);
        return $filters;
    }

    protected function generateHtmlFilters($names){
        $filters = array();
        foreach($names as $name)
            $filters[$name] = new \Twig_Filter_Method($this,$name,array('is_safe' => array('html')));
        return $filters;
    }
}
