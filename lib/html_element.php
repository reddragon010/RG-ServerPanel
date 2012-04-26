<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 24.04.12
 * Time: 22:01
 * To change this template use File | Settings | File Templates.
 */
class HtmlElement
{
    protected  $tag;
    protected  $attributes = array();
    public  $content;

    public function __construct($tag){
        $this->tag = $tag;
    }

    public function __unset($name){
        if(!property_exists($this,$name) && isset($this->attributes[$name]))
            $this->removeAttribute($name);
    }

    public function __get($name){
        if(!property_exists($this,$name) && isset($this->attributes[$name]))
            return $this->attributes[$name];
    }

    public function __set($name, $value){
        if(!property_exists($this,$name))
            $this->setAttribute($name,$value);
    }

    protected function setAttribute($name, $value){
        $this->attributes[$name] = $value;
    }

    protected function removeAttribute($name){
        if(isset($this->attributes[$name]))
            unset($this->attributes[$name]);
    }

    public function __toString(){
        $op = '<' . $this->tag;

        if(count($this->attributes) > 0){
            foreach ($this->attributes as $key=>$value) {
                $op .= ' ' . $key . '="';

                if(is_array($value))
                    $op .= implode(' ', $value);
                else
                    $op .= $value;

                $op .= '"';
            }
        }

        $op .= '>';

        if($this->content != null)
            if(is_array($this->content))
                $op .= implode('', $this->content);
            else
                $op .= $this->content;

        $op .= '</' . $this->tag . '>';
        return $op;
    }
}
