<?php
/**
* 
*/
class session_controller extends Controller
{
	function add(){
		$this->render('login.tpl');
	}
	
	function create($params){
		if(isset($params['login_username']) && isset($params['login_password'])){
			if(!empty($params['login_username']) && !empty($params['login_password'])){
				$user = new User;
				if($user->login($params['login_username'],$params['login_password'])){
					$this->flash('success','Login successful!');
					$this->render_ajax('success',"Login erfolgreich!");
				} else {
					$this->render_ajax('error',"Benutzername/Passwort nicht existent oder inkorrekt!");	
				}
			} else{
				$this->render_ajax('error',"Name oder Passwort wurden nicht angegeben!");
			}
		}
	}
	
	function delete(){
		global $user;
		if($user->logout()){
			session_start();
			$this->flash('success', "erfolgreich ausgeloggt!");
		}
		$this->redirect_to('news','index');
	}
}
