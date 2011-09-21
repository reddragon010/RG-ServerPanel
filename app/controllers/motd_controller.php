<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of RG-ServerPanel.
 *
 *    RG-ServerPanel is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    RG-ServerPanel is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with RG-ServerPanel.  If not, see <http://www.gnu.org/licenses/>.
 */

class MotdController extends BaseController {
    function edit(){
        $file = new PrivateFile('motd');
        $this->render(array(
            'content' => $file->get()
        ));
    }
    
    function update($params){
        if(isset($params['content'])){
            $params['content'] = $this->unescape($params['content']);
            $file = new PrivateFile('motd');
            $file->put($params['content']);
            if($file->get() == $params['content']){
                $this->render_ajax('success', 'MOTD successfully edited');
            } else {
                $this->render_ajax('error', 'An error occured');
            }
        }
    }
    
    private function unescape($text){
        return str_replace('\\', '', $text);
    }
}

?>
