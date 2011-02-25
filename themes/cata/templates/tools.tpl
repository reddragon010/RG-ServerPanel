{% extends "base.tpl" %}

{% block content %}
<div style="padding:10px; margin: auto; width: 600px">
	<a class="tool_box" href="{{root_url}}/repotracker/index">
		<span class="tool_box_title">Repo Tracker</span>
		<span class="tool_box_desc">Aktueller Fixstand</span>
		</a>

	<a class="tool_box" href="tools.php">
		<span class="tool_box_title">Wirb einen Freund</span>
		<span class="tool_box_desc">Im Aufbau</span>
		</a>

	<a class="tool_box" href="{{root_url}}/boss/index">
		<span class="tool_box_title">Instanz Tests</span>
		<span class="tool_box_desc">Im Aufbau</span>
		</a>
		
		<a class="tool_box" href="{{root_url}}/ticket/index">
			<span class="tool_box_title">Tickets</span>
			<span class="tool_box_desc">Wenn du einen Bug melden willst oder ein sonstiges Problem hast erstelle hier ein Ticket</span>
			</a>
<div style="clear:both"></div>
</div>

{% endblock %}