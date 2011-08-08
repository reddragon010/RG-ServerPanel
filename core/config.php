<?php

class Config extends SingletonStore {
    protected $name;
    protected $content;

    protected function init($name){
        $this->name = $name;
        if(self::exists($name)){
            $this->content = sfYaml::load(CONFIG_ROOT . '/' . $name . CONFIG_ENDING);
        } else {
            throw new Exception("Config-File '$name' doesn't exist");
        }
    }
    
    public function get_value(/* key_level1, key_level2, ... */){
        $keys = func_get_args();
        $tmp = $this->content;
        foreach($keys as $i=>$key){
            if(is_array($tmp) && isset($tmp[$key])){
                $tmp = $tmp[$key];
            }elseif(!$i != count($keys)){
                throw new Exception("Config-Key '".var_export($keys, true)."' not found");
            }
        }
        return $tmp;
    }
    
    public static function exists($name){
        return file_exists(CONFIG_ROOT . '/' . $name . CONFIG_ENDING);
    }
}
