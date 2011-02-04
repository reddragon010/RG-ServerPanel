<div id="login">
	<form method="post" id="form2" name="form2" onSubmit="return checkForm2();" action="login.php">
		<div id="loginbutton"><input id="submit" type="submit" name="submit2" value="Login"></div>
    
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