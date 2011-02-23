<?php
/**
* 
*/
class news_controller extends Controller
{
	function index(){
		$news = News::find_all(array(),'date DESC');
		$this->render('site_home.tpl', array('news' => $news));
	}
	
	function add(){
		
	}
	
	function create(){
		
	}
	
	function edit(){
		
	}
	
	function update(){
		
	}
}
