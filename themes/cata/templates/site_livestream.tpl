{% extends "base.tpl" %}

{% block content %}
<div id="content_title_livestream"></div>
<br />
<br />

{% if count > 0 %}
<table bgcolor="#CCCCCC" style="border:1px solid #999; border-collapse:collapse;">
	{% for livestreams in livestream %}
    <tr>
        <td>ID: {{ livestream.id }}</td>
        <td>Url: {{ livestream.url }}</td>
        <td>User: {{ livestream.user }}</td>
        <td>Titel: {{ livestream.title }}</td>
        <td>Beschreibung: {{ livestream.content }}</td>
    </tr>
    <tr>
    	<td colspan="5">
<a href="javascript:showStream({{ livestream.url }});">
            <object width="200" height="150"><br />
            <param name="allowScriptAccess" value="always" /><br />
            <param name="flashvars" value="autoPlay=true&channel=xfire_{{ livestream.url }}&embed=true" /><br />
            <embed src="http://media.xfire.com/swf/livevideoplayer.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="200" height="150" flashvars="autoPlay=false&channel=xfire_{{ livestream.url }}&embed=true"></embed><br />
            </object>
</a>
        </td>
    </tr>
    {% endfor %}
</table>
{% else %}
	<b>No Livestreams are availible!</b>
{% endif %}

{% endblock %}