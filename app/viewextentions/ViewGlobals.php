<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of RG-ServerPanel.
 *
 *    RG-ServerPanel is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    RG-ServerPanel is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with RG-ServerPanel.  If not, see <http://www.gnu.org/licenses/>.
 */

use Dreamblaze\Framework\Core\Kernel;
use Dreamblaze\Framework\Core\Router;

class ViewGlobals extends \Dreamblaze\Framework\Core\ViewExtention {
    public function getGlobals(){
        return array(
            'current_user' => User::$current,
            'rooturl' => Kernel::$request->root_url,
            'params' => Kernel::$request->params,
            'pagetitle' => get_class(Router::$controller) . ' / ' . Router::$action
        );
    }

}