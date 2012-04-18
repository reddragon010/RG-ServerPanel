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

class FirephpLogger implements GenericLogger_Observer {

    private $opts;

    public function __construct($opts=array()) {
        if(empty($opts)){
            $opts = array(  'maxObjectDepth' => 5,
                            'maxArrayDepth' => 5,
                            'maxDepth' => 10,
                            'useNativeJsonEncode' => true,
                            'includeLineNumbers' => true);
        }
        $this->opts = $opts;

        FB::setOptions($opts);
    }

    public function OnInit($level)
    {
        FB::log('Initialized Framework');
    }

    public function OnEnd()
    {
        FB::log('Shutting Down Framework');
    }

    public function OnDebug($msg)
    {
        if(is_array($msg))
            FB::log($msg, 'Array');
        else
            FB::log($msg);
    }

    public function OnNotice($msg)
    {
        FB::info($msg);
    }

    public function OnWarning($msg)
    {
        FB::warn($msg);
    }

    public function OnError($msg)
    {
        FB::error($msg);
    }
}
