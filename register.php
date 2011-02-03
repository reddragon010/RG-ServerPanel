<?php 
require_once('common.php');

if(isset($_POST['register_submit'])){
	$username = protect($_POST['username']);
  $password = protect($_POST['password']);
  $confirm = protect($_POST['passconf']);
  $email = protect($_POST['email']);
	$flags = "2";
	$user = new User;
	$errors = check_registration($username,$password, $confirm, $email, $flags);
  if(count($errors) <= 0){
		if($user->register($username,$password, $email, $flags)){
			flash('success',"Thank you for registering, please login!");
		} else {
			flash('error', "ERROR");
		}
   	} else {
    	foreach($errors AS $error){
      	  $errors .= $error . "<br />";
      }
			flash('error', $error);
   }
	header("Location: index.php");
}
?>
