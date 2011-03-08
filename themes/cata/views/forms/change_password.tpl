<form method="post" id="form" name="form" action="{{root_url}}/user/update">
	<table>
		<tr>
			<td><label for="password">Neues Passwort</lable></td>
			<td><input type="password" name="password" value="{{ user.userdata.password }}"></td>
		</tr>
		<tr>
			<td><label for="password_confirm">Passwort bestätigen</lable></td>
			<td><input type="password" name="password_confirm"></td>
		</tr>
	</table>
	<input type="submit" name="submit" value="Ändern">
</form>