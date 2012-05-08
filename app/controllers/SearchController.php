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

class SearchController extends ApplicationController {
    public function index($params) {
        if(isset($params['query']) && isset($params['type']) && isset($params['search'])){
            $query = $params['query'];
            $model = $params['type'];
            $controller = strtolower($model::$plural);
            $action = 'index';
            $field = $this->get_search_field($query, $params['type']);
            $this->redirect_to(array($controller, $action),array(urlencode($field) => urlencode($query)));
        } else {
            $this->flash('error', 'Invalid Search Request!');
            $this->redirect_back();
        }
    }
    
    private function get_search_field($query, $model){
        if(is_numeric($query)){
            if(isset($model::$primary_key)){
                return $model::$primary_key;
            } else {
                return 'id';
            }
        } elseif(substr_count($query, '.') == 3 && in_array('last_ip', $model::$fields)){
            return 'last_ip';
        } elseif(strpos($query, '@') !== false && in_array('email', $model::$fields)){
            return 'email';
        } elseif(isset($model::$name_field)) {
            return $model::$name_field;
        } else {
            throw new Exception('No matching field found for this query-type combination');
        }
    }
}
