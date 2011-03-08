{% extends "base.tpl" %}

{% block content %}
<div id="content_title_livestream"></div>
<br />
<br />
{% if user.logged_in %}
{% if count > 0 %}
<table style="border:0px;">
	{% for stream in livestreams %}
    <tr>
        <td>ID: {{ stream.id }}</td>
        <td>Url: {{ stream.url }}</td>
        <td>User: {{ stream.user }}</td>
        <td>Titel: {{ stream.title }}</td>
        <td>Beschreibung: {{ stream.content }}</td>
    </tr>
    <tr>
    	<td colspan="4">
<a href="show/stream={{ stream.shortlink }}" target="_blank">show<br /></a>
            <object width="200" height="150"><br />
            <param name="allowScriptAccess" value="always" />
            <param name="flashvars" value="autoPlay=true&channel=xfire_{{ stream.shortlink }}&embed=true" />
            <embed src="http://media.xfire.com/swf/livevideoplayer.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="200" height="150" flashvars="autoPlay=false&channel=xfire_{{ streams.shortlink }}&embed=true"></embed>
            </object>
<br />
        </td>
        <td align="center" valign="bottom">
        	<a href="delete/id={{ stream.id }}">Delete</a>
        </td>
    </tr>
    {% endfor %}
</table>
{% else %}
	<b>No Livestreams are availible!</b>
{% endif %}

<p><a href="add" title="addLiveStream" form-height="310" form-width="300" class="modalform">Add livestream</a></p>
{% endif %}
{% endblock %}