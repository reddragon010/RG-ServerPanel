<?php 
if(isset($_POST['register_submit'])){
	$username = protect($_POST['username']);
  $password = protect($_POST['password']);
  $confirm = protect($_POST['passconf']);
  $email = protect($_POST['email']);
	$flags = "2";
	$user = new User;
	$errors = check_registration($username,$password, $confirm, $email, $flags);
  if(count($errors) <= 0){
		if($user->register($username,$password, $confirm, $email, $flags)){
			echo "<strong><font align=\"center\"><br><br><center>Thank you for registering,<b> " . $_POST['username'] . 		"</b>!</font></strong></center><br>click <a href=\"http://78.46.85.239/test/index.php\"><font color=\"yellow\">here</font></a> for login";
		} else {
			echo "ERROR";
		}
   	} else {
    	foreach($errors AS $error){
      	echo $error . "<br />";
      }
   }
}
?>
