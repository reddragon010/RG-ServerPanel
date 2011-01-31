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
    <b> Register </b> (Design in bearbeitung..)
  		
		<div id="fehlercodeRegister" style="color:red;">
    <?php
			if (isset ($_REQUEST["fehlerR"])){  
				echo $_REQUEST["fehlerR"];
			}
		?>
    </div>
          
		<form method="post" id="form1" name="form1" onSubmit="return checkForm();" action="index.php">
    	<table border="0" cellspacing="0" cellpadding="0">
      	<tr>
					<th>Username</th>
					<td><input class="logininputs" type="text" name="username"></td>
				</tr>
        <tr>
					<th>Password</th>
					<td><input class="logininputs" type="password" name="password"></td>
				</tr>
        <tr>
					<th>Password<br /><span style="font-weight:100; font-size:8pt">Confirm</span></th>
         	<td><input class="logininputs" type="password" name="passconf"></td></tr>
        <tr>
					<th>E-Mail</th>
					<td><input class="logininputs" type="text" name="email"></td>
				</tr>
        <tr>
          <td></td>
	  			<td align="right"><input type="submit" name="register_submit" value=".:: Register ::."></td>
        </tr>
      </table>
    </form>