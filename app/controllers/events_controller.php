<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of events_controller
 *
 * @author mriedmann
 */
class EventsController extends BaseController{
    function index($params){
        if(isset($params['type']) && $params['type'] == 'all')
            unset($params['type']);
        
        $types = array('all' => 'all');
        foreach(Event::$types as $key=>$val){
            $type_id = constant('Event::' . $key);
            $types[$type_id] = $val;
        }
        
        $target_types = Event::find('all', array('group_by' => 'target_class'));
        $target_types = array_map(function($elem){ return $elem->target_class; },$target_types);
        $events_count = Event::count(array('conditions' => $params));
        
        $events = Event::find('all', array('conditions' => $params, 'order' => 'created_at DESC'));
        
        $this->render(array(
            'events' => $events,
            'events_count' => $events_count,
            'types' => $types,
            'target_types' => $target_types
        ));
    }
}

?>
