<?php
/**
* 
*/
class repotracker_controller extends Controller
{
	
	function index()
	{
		global $config;
		$feed = new SimplePie();
		$feed->set_feed_url($config['repos']);
		$feed->enable_order_by_date(true);
		$success = $feed->init();
		$feed->handle_content_type();

		if($success){
			$this->render('tool_repotracker.tpl',array('feed' => $feed));
		}
	}
}
