{% extends "base.tpl" %}

{% block content %}
<div id="content_title_livestream"></div>
<br />
<br />
{% if user.logged_in %}
{% if count > 0 %}
<table style="border:0px;">
	{% for livestreams in livestreams %}
    <tr>
        <td>ID: {{ livestreams.id }}</td>
        <td>Url: {{ livestreams.url }}</td>
        <td>User: {{ livestreams.user }}</td>
        <td>Titel: {{ livestreams.title }}</td>
        <td>Beschreibung: {{ livestreams.content }}</td>
    </tr>
    <tr>
    	<td colspan="4">
<a href="showlivestream.php?stream={{ livestreams.shortlink }}" target="_blank">show<br /></a>
            <object width="200" height="150"><br />
            <param name="allowScriptAccess" value="always" />
            <param name="flashvars" value="autoPlay=true&channel=xfire_{{ livestreams.shortlink }}&embed=true" />
            <embed src="http://media.xfire.com/swf/livevideoplayer.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="200" height="150" flashvars="autoPlay=false&channel=xfire_{{ livestreams.shortlink }}&embed=true"></embed>
            </object>
<br />
        </td>
        <td align="center" valign="bottom">
        	<a href="site_livestream.php?id={{ livestreams.id }}">Delete</a>
        </td>
    </tr>
    {% endfor %}
</table>
{% else %}
	<b>No Livestreams are availible!</b>
{% endif %}

<p><a href="addlivestream.php" title="addLiveStream" form-height="310" form-width="300" class="modalform">Add livestream</a></p>
{% endif %}
{% endblock %}