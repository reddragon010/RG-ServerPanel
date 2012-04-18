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

class UiLogger implements LoggingObserver {

    private $opts;
    private $engine;
    private $loaded = false;

    public function __construct($opts=array()) {
        if(empty($opts)){
            $opts = array(
                'render_type'          => 'HTML',    // Renderer type
                'render_mode'          => 'Div',     // Renderer mode
                'restrict_access'      => false,     // Restrict access of debug
                'allow_url_access'     => false,      // Allow url access
                'enable_watch'         => false,     // Enable wath of vars
                'replace_errorhandler' => true,      // Replace the php error handler
                'lang'                 => 'EN',      // Lang

                // Renderer specific
                'HTML_DIV_view_source_script_name' => 'PHP_Debug_ShowSource.php',
                'HTML_DIV_view_source_excluded_template' => false,
                'HTML_DIV_remove_templates_pattern' => true,
                'HTML_DIV_templates_pattern' => array('/var/www-protected/php-debug.com' => '/var/www/php-debug'),
                'HTML_DIV_images_path' => '/images/phpdebug',
                'HTML_DIV_css_path' => '/css/phpdebug',
                'HTML_DIV_js_path' => '/js/phpdebug',
            );
        }
        $this->opts = $opts;
    }

    private function setup() {
        if (!$this->loaded) {
            $this->load();
            $this->loaded = true;
        }
        if (!isset($this->engine)) {
            $this->engine = new PHP_Debug($this->opts);
        }
    }

    private function load() {
        PHP_Debug_Autoloader::register();
        set_include_path(FRAMEWORK_ROOT . '/lib/PHP_Debug/' . PATH_SEPARATOR . get_include_path());
    }

    public function OnInit($level)
    {
        $this->setup();
        $this->engine->add('Initialized Framework');
    }

    public function OnEnd()
    {
        $this->engine->add('Shutting Down Framework');
    }

    public function OnDebug($msg)
    {
        $this->engine->add($msg);
    }

    public function OnNotice($msg)
    {
        $this->engine->add($msg);
    }

    public function OnWarning($msg)
    {
        $this->engine->add($msg);
    }

    public function OnError($msg)
    {
        $this->engine->error($msg);
    }

    public function display(){
        $this->engine->display();
    }
}
