<?php 
require_once('common.php');
if(!empty($_POST)){
	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = protect($_POST['username']);
	  $password = protect($_POST['password']);
	  $confirm = protect($_POST['passconf']);
	  $email = protect($_POST['email']);
		$flags = "2";
		$user = new User;
		$errors = check_registration($username,$password, $confirm, $email, $flags);
	  if(count($errors) <= 0){
			if($user->register($username,$password, $email, $flags)){
				flash('success','Thank you for registering, please login!');
				return_ajax('success', "Thank you for registering, please login!");
			} else {
				flash('error', 'Registration Error');
				return_ajax('error', 'Registration Error');
			}
	   	} else {
	    	foreach($errors AS $error){
	      	  $errors .= $error . "<br />";
	      }
				flash('error', $error);
				return_ajax('error', $errors);
	   }
	} else {
		return_ajax('success', "");
	}
} else {
	if($config['registration']){
		$tpl = $twig->loadTemplate('register.tpl');
		echo $tpl->render(array());
	} else {
?>
<form action="register.php" method="post" accept-charset="utf-8">
	<p>Registration Deaktiviert</p>
	<input type="hidden" name="ok" value="ok" id="ok">
	<p><input type="submit" value="Ok"></p>
</form>
<?php		
	}
}
?>


