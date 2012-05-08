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

use Dreamblaze\Helpers\SingletonStore;
use Exception;
use Symfony\Component\Yaml\Yaml;

class Config extends SingletonStore {
    protected $name;
    protected $content;

    protected function init($name){
        $this->name = $name;
        $this->content = self::get_config($name);
        $this->save_to_cache();
    }
    
    public function get_value($keys){
        if(is_array($keys))
            $keys = array_filter($keys);
        else
            $keys = array($keys);

        $tmp = $this->content;
        foreach($keys as $i=>$key){
            if(is_array($tmp) && isset($tmp[$key])){
                $tmp = $tmp[$key];
            }elseif(!$i != count($keys)){
                throw new Exception("Config-Key ".var_export($keys, true)." not found");
            }
        }
        return $tmp;
    }

    private function get_config(){
        if($this->cache_exists() && $this->cache_uptodate())
            return $this->get_from_cache();
        elseif($this->config_exists())
            return $this->get_from_file();
        else
            throw new Exception("Config-File '{$this->name}' doesn't exist");
    }

    private function get_from_cache(){
        return unserialize(file_get_contents($this->get_cachefile_path()));
    }

    private function get_from_file(){
        return Yaml::parse($this->get_configfile_path());
    }

    private function save_to_cache(){
        if(!$this->cache_uptodate()){
            file_put_contents($this->get_cachefile_path(),serialize($this->content));
            touch($this->get_configfile_path());
            Logger::debug("Rewriting Cache", $this->name);
        }
    }

    public function config_exists(){
        return file_exists($this->get_configfile_path());
    }

    private function cache_exists(){
        return file_exists($this->get_cachefile_path());
    }

    private function cache_uptodate(){
        return $this->cache_exists() && filemtime($this->get_cachefile_path()) == filemtime($this->get_configfile_path());
    }

    private function get_configfile_path(){
        return CONFIG_ROOT . '/' . $this->name . CONFIG_ENDING;
    }

    private function get_cachefile_path(){
        return FRAMEWORK_ROOT . '/cache/config/' . $this->name . CONFIG_ENDING . '.cache';
    }

}
