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
    public function OnDebug($msg,$label=null);
    public function OnNotice($msg,$label=null);
    public function OnWarning($msg,$label=null);
    public function OnError($msg,$label=null);
    public function OnGroupEnter($label);
    public function OnGroupLeave();
}
