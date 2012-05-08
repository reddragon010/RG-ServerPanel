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
use Dreamblaze\Framework\Core\Logger;

class TemplateEngine extends \Dreamblaze\Helpers\Singleton {

    private $opts;
    private $twig;

    protected function init(){}
    
    public function set_opts($opts){
        $this->opts = $opts;
    }

    public function get_rendered_template($template, $data=array()) {
        Logger::enter_group('View');
        $template = $this->twig->loadTemplate($template);
        $rendered_template = $template->render($data);
        Logger::leave_group();
        return $rendered_template;
    }

    public function register_function($class, $function_name, $method_name, $options=array()) {
        $this->twig->addFunction($function_name, new Twig_Function_Function('\\' . get_class($class) . '::' . $method_name, $options));
    }

    public function register_filter($class, $filter_name, $method_name, $options=array()) {
        $this->twig->addFilter($filter_name, new Twig_Filter_Function('\\' . get_class($class) . '::' . $method_name, $options));
    }

    public function register_global($class, $global_name, $method_name, $options=array()) {
        $value = call_user_func('\\' . get_class($class) . '::' . $method_name);
        $this->twig->addGlobal($global_name, $value);
    }

    public function load() {
        Logger::debug('Loading Template-Engine');
        $loader = $this->get_loader();     
        $config = $this->get_config();
        $this->twig = new \Twig_Environment($loader, $config);
        $this->twig = \ViewPreloader::pre_load($this->twig);
    }

    private function get_config() {
        if ($this->opts['cache']) {
            $cache = APP_ROOT . '/cache/views';
        } else {
            $cache = false;
        }

        $config = array(
            'cache' => $cache,
            'debug' => $this->opts['debug']
        );
        return $config;
    }

    private function get_loader() {
        return new \Twig_Loader_Filesystem($this->opts['loader']);
    }

}
