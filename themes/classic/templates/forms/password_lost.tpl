{% extends "base.tpl" %}

{% block content %}
<div id="password_lost">
	<form method="post" id="password_lost_form" name="form" action="password_lost.php">
		Deine E-Mail Adresse: <input type="text" name="email">
		<input id="submit" type="submit" name="submit" value="Passwort-Link anfordern">
	</form>
</div>
{% endblock %}