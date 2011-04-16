
<form method="post"  name="form" action="{{rooturl}}/user/create" accept-charset="utf-8">
	{% if active %}
	<table>
		<tr>
			<td><label for="username">Username</lable></td>
			<td><input class="logininputs" type="text" name="username"></td>
		</tr>
		<tr>
			<td><label for="password">Password</lable></td>
			<td><input class="logininputs" type="password" name="password"></td>
		</tr>
		<tr>
			<td><label for="passconf">Password Confirm</lable></td>
			<td><input class="logininputs" type="password" name="passconf"></td>
		</tr>
		<tr>
			<td><label for="email">E-Mail</lable></td>
			<td><input class="logininputs" type="text" name="email"></td>
		</tr>
	</table>
	<input type="submit" name="submit" value="Register">
	{% else %}
	<p>Registration Deaktiviert</p>
	<input type="hidden" name="ok" value="ok" id="ok">
	<input type="submit" value="Ok">
</form>
{% endif %}