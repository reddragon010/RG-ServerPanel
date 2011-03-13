{% extends "base.tpl" %}

{% block content %}
<div id="results">

	{% for item in feed.get_items %}
		{% set date = item.get_date('d. F, o') %}
			{% if date != prevdate %}
			<div class="date_entry">
				<h3>{{ date }}</h3>
			</div>
			{% else %}
			<div class="entry" onClick="$('#entry_c{{ loop.index }}').slideToggle()">
				<div>
					<h4><a href="{{ item.get_permalink }}">{{ item.get_title|raw }}</a></h4>
					<div class="footnote">{{ item.get_date('U')|time_ago }} {{ item.get_author|format_author }} {{ item.get_feed.get_title|format_repo }}</div>
				</div>
				<div style="clear:both"></div>
				<div id="entry_c{{ loop.index }}" style="display: none;">
					{{ item.get_content|raw }}
				</div>
			</div>
			{% endif %}
		{% set prevdate = date %}
	{% endfor %}
</div>
{% endblock %}