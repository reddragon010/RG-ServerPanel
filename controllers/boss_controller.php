<?php
/**
* 
*/
class boss_controller extends Controller
{
	
	function index()
	{
		global $user;
		if($user->logged_in()){
			//Load Running Tests
			//$running_tests = Boss::find_running_tests();
			$running_tests = Boss::find_all(array('test_start < NOW()','test_end > NOW()'));
			
			//Load Upcoming Tests
			$upcoming_tests = Boss::find_upcoming_tests();
		
			//Load Ini Data
			$instances = Instance::find_all();

			$this->render('tool_initests.tpl',array('running_tests' => $running_tests, 'upcoming_tests' => $upcoming_tests, 'instances' => $instances));
		} else {
			$this->flash('error','Bitte einloggen');
			$this->redirect_to('tools','index');
		}
	}
	
	function edit($params){
		$boss = Boss::find(array("id={$params['id']}"));
		$boss->instance_id = $params['iid'];
		$this->render('admin_boss_edit.tpl',array('boss' => $boss));
	}
	
	function update($params){
		if(empty($params['test_start']) || empty($params['test_end'])){
			$params['test_start'] = '0000-00-00 00:00:00';
			$params['test_end'] = '0000-00-00 00:00:00';
		}
		$boss = new Boss($params,false);
		
		if(!$boss->save()){
			$this->render_ajax('error', mysql_error());
			exit();
		} else {
			$this->render_ajax('success', 'DONE!');
		}	
	}
}
