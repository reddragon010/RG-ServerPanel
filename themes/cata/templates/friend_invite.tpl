{% extends "base.tpl" %}

{% block content %}
<div id="invite">
	<form method="post" id="invite_form" name="form" action="friend_invite.php">
		E-Mail des Freunds <input type="text" name="invite_email">
		<input id="submit" type="submit" name="submit" value="Abschicken">
	</form>
</div>
{% endblock %}