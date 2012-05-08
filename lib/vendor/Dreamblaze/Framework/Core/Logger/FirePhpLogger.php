<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of StupidPrlf.
 *
 *    StupidPrlf is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    StupidPrlf is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with StupidPrlf.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Dreamblaze\Framework\Core\Logger;
use Dreamblaze\GenericLogger\Observer;

class FirePhpLogger implements Observer {

    private $opts;
    private $starttime;
    private $grouptime = array();

    public function __construct($opts=array()) {
        if(empty($opts)){
            $opts = array(  'maxObjectDepth' => 5,
                            'maxArrayDepth' => 5,
                            'maxDepth' => 10,
                            'useNativeJsonEncode' => true,
                            'includeLineNumbers' => true);
        }
        $this->opts = $opts;

        \FB::setOptions($opts);
    }

    public function OnInit($level)
    {
        $this->starttime = microtime(true);
        \FB::log('Initialized Framework');
    }

    public function OnEnd()
    {
        $difftime = floor((microtime(true) - $this->starttime)*1000);
        \FB::log('Shutting Down Framework (' . $difftime . 'ms)');
    }

    public function OnDebug($msg,$label=null)
    {
        \FB::log($msg, $label);
    }

    public function OnNotice($msg,$label=null)
    {
        \FB::info($msg, $label);
    }

    public function OnWarning($msg,$label=null)
    {
        \FB::warn($msg, $label);
    }

    public function OnError($msg,$label=null)
    {
        \FB::error($msg, $label);
    }

    public function OnGroupEnter($label)
    {
        $this->grouptime[] = microtime(true);
        \FB::group($label);
    }

    public function OnGroupLeave()
    {
        $gtime = array_pop($this->grouptime);
        \FB::log("Time:" . floor((microtime(true) - $gtime)*1000));
        \FB::groupEnd();
    }
}
