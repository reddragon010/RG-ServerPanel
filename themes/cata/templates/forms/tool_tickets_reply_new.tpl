<form action="tool_tickets_reply_new.php" method="post" accept-charset="utf-8">
	{% if user.is_admin %}
	<label for="status">Status</label>{{ selectArray('status',TICKET_STATUS,ticket.status) }}
	{% endif %}
	<label for="content">Antwort</label><textarea rows="5" name="content" value=""></textarea>
	<input type="hidden" name="id" value="{{ ticket.id }}" id="id">
	<input type="submit" value="Abschicken">
</form>