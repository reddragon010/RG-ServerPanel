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

class EventsController extends BaseController{
    function index($params){
        if(isset($params['type']) && $params['type'] == 'all')
            unset($params['type']);
        
        if(isset($params['target_class']) && $params['target_class'] == 'all')
            unset($params['target_class']);
        
        $types = array('all' => 'all');
        foreach(Event::$types as $key=>$val){
            $type_id = constant('Event::' . $key);
            $types[$type_id] = $val;
        }
        
        $target_classes = Event::find()->distinct_all('target_class');
        $target_classes = array_map(function($elem){ return $elem->target_class; },$target_classes);
        $target_classes = array_filter($target_classes);

        $events = Event::find()->where($params)->order('created_at DESC')->page($params['page']);
        
        $data = array(
            'events' => $events->all(),
            'events_count' => $events->count(),
            'types' => $types,
            'target_types' => array('all') + $target_classes
        );
        
        if (isset($params['partial'])) {
            $this->render_partial('shared/events', $data);
        } else {
            $this->render($data);
        }
    }
}
