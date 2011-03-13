<form action="{{rooturl}}/ticketreply/create" method="post" accept-charset="utf-8">
	{% if user.is_admin %}
	<label for="status">Status</label>{{ selectArray('status',TICKET_STATUS,ticket.status) }}
	{% endif %}
	<label for="content">Antwort</label><textarea rows="5" name="content" value=""></textarea>
	<input type="hidden" name="ticket_id" value="{{ ticket.id }}" id="id">
	<input type="submit" value="Abschicken">
</form>