<div class="entry" onClick="$('#entry_c{{ ticket.id }}').slideToggle()">
	<div>
		<h4>{{ ticket.title }} -- {{ticket.category_name}}</h4>
		<div class="footnote">
			{{ ticket.updated_at }} 
			{{ ticket.user.userdata.username }} 
			{{ ticket.realm.name }} 
			{{ ticket.character.data.name }} 
			<a href="{{root_url}}/ticketreply/add/id={{ ticket.id }}" class="modalform"><img src="{{ theme_url }}/images/icons/email_go.png"></a>
			<a href="{{root_url}}/ticket/delete/id={{ ticket.id }}"><img src="{{ theme_url }}/images/icons/email_delete.png"></a>
		</div>
	</div>
	<div style="clear:both"></div>
	<div id="entry_c{{ ticket.id }}" style="display: none;">
		<div class="ticket_content">{{ ticket.content }}</div>
		<div class="ticket_replies">
			{% for reply in ticket.replies %}
				<div class="ticket_reply">
					{{reply.user.userdata.username}}
					{{reply.updated_at}}
					{{reply.content}}
				</div>
			{% endfor %}
		</div>
	</div>
</div>