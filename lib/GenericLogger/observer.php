<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 18.04.12
 * Time: 22:05
 * To change this template use File | Settings | File Templates.
 */
interface GenericLogger_Observer
{
    public function OnInit($level);
    public function OnEnd();
    public function OnDebug($msg);
    public function OnNotice($msg);
    public function OnWarning($msg);
    public function OnError($msg);
}
