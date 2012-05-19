<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.05.12
 * Time: 01:55
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Cache;

interface Store
{
    public function exists($key);
    public function write($key, $obj);
    public function read($key);
}
