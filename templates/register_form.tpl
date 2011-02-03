<b> Register </b> (Design in bearbeitung..)
      
<form method="post" id="form1" name="form1" onSubmit="return checkForm();" action="register.php">
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