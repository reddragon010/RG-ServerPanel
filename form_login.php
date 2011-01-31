<?php
$error = "";
if(isset($_POST['login_username']) && isset($_POST['login_password'])){
	if(!empty($_POST['login_username']) && !empty($_POST['login_password'])){
		$user = new User;
		if(!$user->login($_POST['login_username'],$_POST['login_password'])){
			
			$error = "Benutzername/Passwort nicht existent oder inkorrekt!";  	
		}
	} else{
		$error = "Name oder Passwort wurden nicht angegeben!";	
	}
}
?> 

<?php if(!logged_in()){?>
<div id="login">
	<form method="post" id="form2" name="form2" onSubmit="return checkForm2();" action="index.php">
		<div id="loginbutton"><input id="submit" type="submit" name="submit2" value=""></div>
    
		<table border="0" cellspacing="0" cellpadding="0">
		  <tr>
				<td width="80">Username</td>
				<td><input class="logininputs" type="text" name="login_username"></td>
			</tr>
		  <tr>
				<td width="80">Passwort</td>
				<td><input class="logininputs" type="password" name="login_password"></td>
			</tr>
		  <tr>
		    <td colspan="2">
					<div id="fehlercodeLogin">
						<?php echo $error;?>
					</div>
		    </td>
		  </tr>
		</table>
	</form>
</div>
<?php } else { ?>
<div id="willkommen">
    <?php echo $_SESSION['userdata']['username'];?>,
    <a href="index.php?a=logout">logout</a><br />
    <div>
			<a href="index.php?a=my_characters">MyChars</a>
		</div>
    <!-- USER DATA -->
    <div id="userdata">
    	user-id:&nbsp; <?php echo $_SESSION['userid']; ?> <br />
      gm-level:&nbsp;<font color="#CC0000"><?php echo $_SESSION['userdata']['gmlevel']; ?></font><br />
    </div>
</div>
<?php } ?>