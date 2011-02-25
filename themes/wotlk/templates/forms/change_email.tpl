<form method="post" id="form" name="form" action="user_change_email.php">
	<table>
		<tr>
			<td><label for="email">E-Mail</lable></td>
			<td><input type="text" name="email" value="{{ user.userdata.email }}"></td>
		</tr>
		<tr>
			<td><label for="email_confirm">E-Mail bestätigen</lable></td>
			<td><input type="text" name="email_confirm"></td>
		</tr>
	</table>
	<input type="submit" name="submit" value="Ändern">
</form>