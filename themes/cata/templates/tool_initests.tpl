{% extends "base.tpl" %}

{% block content %}

{% if running_tests is empty %}
	<h2>Laufende Tests</h2>
	<div id="running_tests">
		<table>
			<thead>
				<tr>
					<th style="width: 300px">Boss</th>
					<th>Status</th>
					<th>Start</th>
					<th>Ende</th>
					<th style="width: 30px"></th>
				</tr>
			</thead>
			<tbody>
				{% for boss in running_tests %}
				<tr>
					<td>{{boss.name}}</td>
					<td>{{STATUS[boss.status]}}</td>
					<td>{{boss.start}}</td>
					<td>{{boss.end}}</td>
					<td> {% if boss.comment is not empty %}<a href="#" class="tooltip" title="{{boss.comment}}"><img src="{{theme_url}}/images/note.png"></a>{% endif %}</td>
				</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>
{% endif %}
{% if upcoming_tests is empty %}
	<h2>Anstehende Tests</h2>
	<div id="upcoming_tests">
		<table>
			<thead>
				<tr>
					<th style="width: 300px">Boss</th>
					<th>Status</th>
					<th>Start</th>
					<th>Ende</th>
					<th style="width: 30px"></th>
				</tr>
			</thead>
			<tbody>
				{% for boss in upcoming_tests %}
				<tr>
					<td{{boss.name}}</td>
					<td>{{STATUS[boss.status]}}</td>
					<td>{{boss.start}}</td>
					<td>{{boss.end}}</td>
					<td>{% if boss.comment is not empty %}<a href="#" class="tooltip" title="{{boss.comment}}"><img src="{{theme_url}}/images/icons/note.png"></a>{% endif %}</td>
				</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>
{% endif %}
	<div id="instances">
		{% for ini in instances %}
		{% if ini.bosses is defined %}
		<table style="border: none; margin: 0">
			<tr>
				<td><h2>{{ini.name}}</h2></td>
				<td class="status">Status</td>
				<td style="width: 150px">{{ progress_bar(10000 + ini.id, ini.status, ini.bosses_count * 3) }}</td>
			</tr>
		</table>
		<table>
			<tbody>
				{% for boss in ini.bosses %}
				<tr>
					<td style="width: 40px">{{ boss.icon|boss_icon }}</td>
					<td>{{boss.name}}</td>
					<td>
						{% if boss.test_start != '0000-00-00 00:00:00' %}
						von {{boss['UNIX_TIMESTAMP(test_start)']|date('d.m.o H:i')}}<br />
						bis {{boss['UNIX_TIMESTAMP(test_end)']|date('d.m.o H:i')}}
						{% endif %}
					</td>
					<td class="status">{{ STATUS[boss.status] }}</td>
					<td style="width: 150px">{{ progress_bar(boss.id,boss.status,3) }}</td>
					<td>
						{% if boss.comment is not empty %}<a href="#" class="tooltip" title="{{boss.comment}}"><img src="{{theme_url}}/images/icons/note.png"></a>{% endif %}
						{% if user.is_admin %}<a href="{{root_url}}/boss/edit/id={{boss.id}}&iid={{boss.instance_id}}" class="modalform" title="Boss Bearbeiten"><img src="{{theme_url}}/images/icons/page_edit.png"></a>{% endif %}
					</td>
					
				</tr>
				{% endfor %}
			</tbody>
		</table>
		{% endif %}
		{% endfor %}
	</div>
{% endblock %}