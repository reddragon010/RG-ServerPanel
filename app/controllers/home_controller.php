<?php
class HomeController extends BaseController {

    var $before = array(
        'check_login'
    );

    function index() {
        $recent_events = Event::find('all', array('conditions' => array('created_at >= NOW() - INTERVAL 1 WEEK'), 'order' => 'created_at DESC'));
        $motd_file = new PrivateFile('motd');
        $motd = $motd_file->get();
        $this->render(array(
            'recent_events' => $recent_events,
            'motd' => $motd,
            'motd_last_update' => $motd_file->age()
        ));
    }

}
