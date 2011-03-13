<?php
/**
* 
*/
class news_controller extends Controller
{
	function index(){
		$news = News::find('all',array('order' => 'date DESC'));
		$this->render(array('news' => $news));
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
