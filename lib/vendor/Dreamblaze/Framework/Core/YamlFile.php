<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.05.12
 * Time: 00:05
 * To change this template use File | Settings | File Templates.
 */
namespace Dreamblaze\Framework\Core;
use Symfony\Component\Yaml\Yaml;

class YamlFile
{
    private $name;
    private $path;

    public $content;

    public function __construct($name, $path){
        $this->name = $name;
        $this->path = $path;
    }

    public function to_array(){
        if($this->content == null)
            $this->load();

        return (array)$this->content;
    }

    public function get_value($keys){
        if($this->content == null)
            $this->load();

        if(is_array($keys))
            $keys = array_filter($keys);
        else
            $keys = array($keys);

        return $this->find_value($keys, $this->content);
    }

    private function find_value($keys, $content){
        if(is_array($keys) && is_array($content) && isset($content[$keys[0]])){
            $tmp = $content[$keys[0]];
            return $this->find_value(array_shift($keys), $tmp);
        }elseif(!is_array($keys)){
            return $content;
        }else{
            if(is_array($keys)) $keys = implode('->',$keys);
            throw new \Exception("Yaml-File-Key '".$keys."' not found in File '{$this->name}'");
        }
    }

    public function load(){
        if(Cache::get('yaml')->exists($this->get_cache_key()))
            $this->load_from_cache();
        else{
            $this->load_from_file();
            $this->save_to_cache();
        }
    }

    private function load_from_file(){
        $filepath = $this->get_file_path();
        if(file_exists($filepath))
            $this->content = (array)Yaml::parse($filepath);
        else
            throw new \Exception("Can't load YML-File '{$this->name}' from '{$filepath}'");
    }

    private function load_from_cache(){
        $this->content = (array)Cache::get('yaml')->read($this->get_cache_key());
    }

    private function save_to_cache(){
        Cache::get('yaml')->write($this->get_cache_key(), $this->content);
    }

    private function get_cache_key(){
        $age = filemtime($this->path);
        return $age . '_' . $this->name;
    }

    private function get_file_path(){
        return $this->path . DIRECTORY_SEPARATOR . $this->name . '.yml';
    }
}
