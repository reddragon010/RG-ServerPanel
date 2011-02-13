{% extends "base.tpl" %}

{% block content %}
<a href="friend_invite.php">Einen Freund einladen</a>

<h3>Ausgeschickte Anfragen</h3>
<table style="width:100%">
	<tr>
		<th>Freund</th>
		<th>E-Mail</th>
	</tr>
	{% for friend in invited_friends %}
	<tr>
		<td>{{ friend.username }}</td>
		<td>{{ friend.email }}</td>
	</tr>
	{% endfor %}
</table>

<h3>Ausgehende Freundschaften</h3>
<table style="width:100%">
	<tr>
		<th>Freund</th>
		<th>von</th>
		<th>bis</th>
	</tr>
	{% for friend in friends %}
	<tr>
		<td>{{ friend.username }}</td>
		<td>{{ friend.bind_date }}</td>
		<td>{{ friend.expire_date }}</td>
	</tr>
	{% endfor %}
</table>

<h3>Eingehende Freundschaften</h3>
<table style="width:100%">
	<tr>
		<th>Freund</th>
		<th>von</th>
		<th>bis</th>
	</tr>
	{% for uuser in users %}
	<tr>
		<td>{{ uuser.username }}</td>
		<td>{{ uuser.bind_date }}</td>
		<td>{{ uuser.expire_date }}</td>
	</tr>
	{% endfor %}
</table>
{% endblock %}