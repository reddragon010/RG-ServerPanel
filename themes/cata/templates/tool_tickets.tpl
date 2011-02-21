{% extends "base.tpl" %}

{% block content %}
<a class="modalform" href="tool_tickets_new.php" title="Ticket erstellen" form-width="700">Neues Ticket</a>

<h4>Neue Tickets</h4>
<div id="new_tickets" class="ticket_box">
{% if new_tickets is empty %}
	Keine Tickets
{% else %}
	{% for ticket in new_tickets %}
			{% include 'tool_tickets_ticket.tpl' %}
	{% endfor %}
{% endif %}
</div>

<h4>Offene Tickets</h4>
<div id="open_tickets" class="ticket_box">
{% if open_tickets is empty %}
	Keine Tickets
{% else %}
	{% for ticket in open_tickets %}
		{% include 'tool_tickets_ticket.tpl' %}
	{% endfor %}
{% endif %}
</div>

<h4>Geschlossene Tickets</h4>
<div id="closed_tickets" class="ticket_box">
{% if closed_tickets is empty %}
	Keine Tickets
{% else %}
	{% for ticket in closed_tickets %}
			{% include 'tool_tickets_ticket.tpl' %}
	{% endfor %}
{% endif %}
</div>
{% endblock %}