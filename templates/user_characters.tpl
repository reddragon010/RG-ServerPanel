{% extends "base.tpl" %}

{% block content %}
<table class="chars">
	<thead>
		<tr>
			<th class="narrow">Avatar</th>
			<th>Name</th>
			<th class="narrow">Level</th>
			<th>Money</th>
			<th class="narrow">Flags</th>
			<th class="narrow"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="header" colspan="6">Main</td>
		</tr>
		{% if main %}
		<tr>
			<td class="narrow"><img src="{{ display_avatar(main) }}" /></td>
			<td>{{ main.data.name }}</td>
			<td class="narrow">{{ main.data.level }}</td>
			<td>{{ display_money(main.data.money) }}</td>
			<td class="narrow">{{ main.data.flags }}</td>
			<td></td>
		</tr>
		{% endif %}
		<tr>
			<td class="header" colspan="6">Chars</td>
		</tr>
		{% for char in user.chars %}
		<tr>
			<td class="narrow"><img src="{{ display_avatar(char) }}" /></td>
			<td>{{ char.data.name }}</td>
			<td class="narrow">{{ char.data.level }}</td>
			<td>{{ display_money(char.data.money) }}</td>
			<td class="narrow">{{ char.data.flags }}</td>
			<td><a href="make_main.php?guid={{ char.guid }}">MakeMain</a></td>
		</tr>
		{% endfor %}
	</tbody>
</table>
{% endblock %}