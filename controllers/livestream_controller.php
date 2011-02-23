<?php
class livestream_controller extends Controller
{
	function index(){
		$count = Livestream::count();
		$livestreams = Livestream::find_all();
		$this->render('site_livestream.tpl', array(
		'count' => $count,
		'livestreams' => $livestreams
		));
	}
	
	function show($params){
		if(isset($params['stream'])){
			if(!empty($params['stream'])){
				$url = $params['stream'];
				$this->render('showLiveStream.tpl',array(
										'url' => $url,
										));
			}
		}
	}
	
	function add(){
		$this->render('addLiveStream.tpl');
	}
	
	function create($params){
		global $user;
		if(isset($params['stream_url']) && isset($params['stream_title']) && isset($params['stream_description'])){
			if(!empty($params['stream_url']) && !empty($params['stream_title']) && !empty($params['stream_description'])){
				$data = array(
					'url' => $params['stream_url'],
					'user_id' => $user->userid,
					'title' => $params['stream_title'],
					'content' => $params['stream_description']
				);
				$stream = Livestream::create($data);
				if($stream){
					$this->render_ajax('success',"Erfolgreich!");
				} else {
					$this->render_ajax('error',"Fehler!, versuchen sie es erneut!");	
				}
			} else{
				$this->render_ajax('error',"URL, Titel oder Beschreibung wurden nicht angegeben!");
			}
		}
	}
	
	function delete($params){
		global $config;
		if(isset($params['id'])){
			if($ls = Livestream::find(array("id = '{$params['id']}'"))){
				if($ls->destroy()){
					flash('success', 'Gelöscht');
				} else {
					flash('error', 'Fehler');
				}
			} else {
				flash('error', 'Stream konnte nicht gefunden werden');
			}
		} else {
			flash('error', 'Keine Id übergeben!');
		}
		header("Location: {$config['page_root']}/livestream/index");
	}
}