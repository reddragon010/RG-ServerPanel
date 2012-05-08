<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.04.12
 * Time: 02:48
 * To change this template use File | Settings | File Templates.
 */

namespace Dreamblaze\Framework\Core;

class Route
{
    public $pattern;
    public $arguments;


    public function __construct($pattern, $arguments){
        $this->pattern = $pattern;
        $this->arguments = $arguments;
    }
}
