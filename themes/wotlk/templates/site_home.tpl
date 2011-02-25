{% extends "base.tpl" %}

{% block content %}
<div id="content_title_home"></div>
<div id="news">
	<div id="news_top"></div>
    <div id="news_container">
        <div id="news_bg_bottom">
            <div id="news_content">
                <div class="news_item firstitem">
                    <span id="letter">P</span><span id="title">atch 3.3.5a</span><span id="date">[salja, 2011-02-04]</span>
                    <br />Die Funktionalität des Ausschlusswahlsystems wird sich nun, basierend
        auf Daten des Dungeonbrowsers, dem Verhalten des Spielers anpassen.
                </div>
                <div class="news_item">
                    <span id="letter">G</span><span id="title">eheimnisse von Ulduar</span><span id="date">[salja, 2011-02-04]</span>
                    <br />In unserem Download Archiv findet ihr immer den aktuellen World of
        Warcraft Patch. In dieser Liste findet ihr drei verschiedene Clienten. Besitzt ihr die aktuelle Version von World of Warcraft, so langt euch der Update Patch.
                </div>
                <div class="news_item lastitem">
                    <span id="letter">G</span><span id="title">eheimnisse von Ulduar</span><span id="date">[salja, 2011-02-04]</span>
                    <br />In unserem Download Archiv findet ihr immer den aktuellen World of
        Warcraft Patch. In dieser Liste findet ihr drei verschiedene Clienten. Besitzt ihr die aktuelle Version von World of Warcraft, so langt euch der Update Patch.
                </div>
        	</div>
        </div>
    </div>
    <div id="news_bottom"></div>
</div>

<!--
<div id="newstext" style="width:390px; margin-left:10px;">
	{% for newsitem in news %}
		<font color=\"#963\">{{ newsitem.id }}# {{ newsitem.title}}:</font><br />
		<font size="-1">{{ newsitem.author }}, {{ newsitem.date }}</font><br />
		{{ newsitem.content }} <br /><br />
	{% endfor %}
</div>
-->
<div id="rightbox"></div>
{% endblock %}
