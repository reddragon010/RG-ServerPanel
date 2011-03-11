{% extends "base.tpl" %}

{% block content %}
<div id="content_title_home"></div>
<div id="news">
	<div id="news_top"></div>
    <div id="news_container">
        <div id="news_bg_bottom">
            <div id="news_content">
	{{news.title}}
								{% for newsitem in news %}
                <div class="news_item firstitem">
                    <span id="letter">{{newsitem.title|substr(0,1)}}</span><span id="title">{{newsitem.title|substr(1)}}</span><span id="date">[{{newsitem.author}}, {{newsitem.date|date("m/d/Y")}}]</span>
										<br />
                    <p>{{newsitem.content}}</p>
                </div>
								{% endfor %}
        	</div>
        </div>
    </div>
    <div id="news_bottom"></div>
</div>
<div id="rightbox"></div>
{% endblock %}
