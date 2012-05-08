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

namespace Dreamblaze\Framework\Core;

class PrivateFile {
    const BASE_PATH = '/private/files/';
    
    private $path;
    private $name;
    
    public function __construct($name){
        $this->name = $name;
        $this->path = FRAMEWORK_ROOT . self::BASE_PATH . $name;
        if(!file_exists($this->path))
            throw new Exception ("File $name under {$this->path} not found!");    
    }
    
    public function get(){
        return file_get_contents( $this->path);
    }
    
    public function put($content){
        return file_put_contents($this->path, $content);
    }
    
    public function age(){
        return filemtime($this->path);
    }
}
