<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mriedmann
 * Date: 19.04.12
 * Time: 02:48
 * To change this template use File | Settings | File Templates.
 */
class Route
{
    public $controller;
    public $action;

    public function __construct($controller, $action){
        $this->controller = $controller;
        $this->action = $action;
    }

    public function follow(){
        $this->controller->execute($this->action);
    }
}
