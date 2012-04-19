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

class Template extends SingletonStore {
    private $name;
    private $theme_name;
    private $tpl_engine;
    private $extentions;
    
    protected function init($name) {
        GenericLogger::enter_group('Template');
        GenericLogger::debug('Loading Template');
        $this->name = $name;
        
        $this->tpl_engine = TemplateEngine::instance();
        $this->tpl_engine->set_opts(array(
            'loader' => array(
                APP_ROOT . "/views/",
                APP_ROOT . "/views/" . $this->name,
                APP_ROOT . "/mails/"),
            'cache' => Environment::get_value('cache'),
            'debug' => Environment::get_value('debug')
        ));
        $this->tpl_engine->load();
        
        $this->load_extentions();
        $this->register_extentions();
    }

    public function render($action, $data=array()) {
        GenericLogger::debug($action ,'Rendering Template');
        GenericLogger::debug($data, 'Template Data');
        echo $this->tpl_engine->get_rendered_template($action . '.tpl.html', $data);
        GenericLogger::leave_group();
    }

    private function load_extentions() {
        $this->extentions = array(
            'global' => new \tplglobals(),
            'filter' => new \tplfilters(),
            'function' => new \tplfunctions()
        );
    }

    private function register_extentions() {
        GenericLogger::debug('Registering Template-Extentions');
        foreach ($this->extentions as $register_name => $class) {
            $methods = get_class_methods($class);
            foreach ($methods as $method_name) {
                $options = array();
                if (strpos($method_name, '_html') == true) {
                    $options['is_safe'][] = 'html';
                    $name = str_replace('_html', '', $method_name);
                }  else {
                    $name = $method_name;
                }
                $this->tpl_engine->{'register_' . $register_name}($class, $name, $method_name, $options);
            }
        }
    }

}

