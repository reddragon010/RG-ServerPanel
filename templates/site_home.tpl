{% extends "base.tpl" %}

{% block content %}

{% if not user.logged_in %}
<div id="register" style="visibility:visible;">
	{% include 'register_form.tpl' %}
</div>
{% endif %}

<div id="newstext" style="width:390px; margin-left:10px;">
	{% for newsitem in news %}
		<font color=\"#963\">{{ newsitem.id }}# {{ newsitem.title}}:</font><br />
		<font size="-1">{{ newsitem.author }}, {{ newsitem.date }}</font><br />
		{{ newsitem.content }} <br /><br />
	{% endfor %}
</div>
{% endblock %}
