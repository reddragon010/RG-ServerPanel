<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.05.12
 * Time: 02:12
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Cache;

class CacheException extends \Exception
{
    public function __construct($msg, $innerEx=null){
        parent::__construct($msg,0,$innerEx);
    }
}
