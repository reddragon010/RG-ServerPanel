<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 22.04.12
 * Time: 22:35
 * To change this template use File | Settings | File Templates.
 */

namespace Dreamblaze\Framework\Core;

class RouteException extends \Exception
{
    public function __construct($message, $code=0, $previous=null)
    {
        parent::__construct($message, $code, $previous);
    }
}
