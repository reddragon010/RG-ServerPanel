<div class="entry" onClick="$('#entry_c{{ ticket.id }}').slideToggle()">
	<div>
		<h4>{{ ticket.title }}</h4>
		<div class="footnote">
			{{ ticket.date }} 
			{{ ticket.user.userdata.username }} 
			{{ ticket.realm.name }} 
			{{ ticket.character.data.name }} 
			<a href="tool_tickets_reply_new.php?id={{ ticket.id }}" class="modalform"><img src="{{ theme_url }}/images/icons/email_go.png"></a>
		</div>
	</div>
	<div style="clear:both"></div>
	<div id="entry_c{{ ticket.id }}" style="display: none;">
		<div class="ticket_content">{{ ticket.content }}</div>
		<div class="ticket_replies">
			{% for reply in ticket.replies %}
				<div class="ticket_reply">
					{{reply.user.userdata.username}}
					{{reply.content}}
				</div>
			{% endfor %}
		</div>
	</div>
</div>