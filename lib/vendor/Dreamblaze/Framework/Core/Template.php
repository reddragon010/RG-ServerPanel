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

class Template extends \Dreamblaze\Helpers\SingletonStore {
    private $name;
    private $tpl_engine;
    
    protected function init($name) {
        Logger::enter_group('Template');
        Logger::debug('Loading Template');
        $this->name = $name;
        
        $this->tpl_engine = TemplateEngine::instance();
        $this->tpl_engine->set_opts(array(
            'loader' => array(
                APP_ROOT . "/views/",
                APP_ROOT . "/views/" . $this->name
            ),
            'cache' => Config::instance('framework')->get_value('cache'),
            'debug' => Config::instance('framework')->get_value('debug')
        ));
        $this->tpl_engine->load();
    }

    public function render($action, $data=array()) {
        Logger::debug($action ,'Rendering Template');
        Logger::debug($data, 'Template Data');
        echo $this->tpl_engine->get_rendered_template($action . '.tpl.html', $data);
        Logger::leave_group();
    }
}

