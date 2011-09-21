<?php

class EventsController extends BaseController{
    function index($params){
        if(isset($params['type']) && $params['type'] == 'all')
            unset($params['type']);
        
        $types = array('all' => 'all');
        foreach(Event::$types as $key=>$val){
            $type_id = constant('Event::' . $key);
            $types[$type_id] = $val;
        }
        
        $target_types = Event::find()->all();
        $target_types = array_map(function($elem){ return get_class($elem->target); },$target_types);
        array_unique($target_types);
        
        $events = Event::find()->where($params)->order('created_at DESC')->page($params['page']);
        
        $this->render(array(
            'events' => $events->all(),
            'events_count' => $events-count(),
            'types' => $types,
            'target_types' => $target_types
        ));
    }
}

?>
