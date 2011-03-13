<div id="useravatar"><img src="{{user.mainchar|avatar}}"></div>
<div class="username">{{ user.userdata.username }}</div>
<div class="chardata">
	{% if not user.mainchar.data.name %}
	keinen Haupt-Charachter ausgewählt
	{% else %}
	{{user.mainchar.data.name}} {{user.mainchar.data.level}} {{user.mainchar.realm.name}}
	{% endif %}
</div>
<div id="user_menu" style="display: none;">
	<a class="item" href="{{rooturl}}/user/characters">
		<h1>Character Verwaltung</h1>
		<p>Wahl des Mainchars</p>
	</a>	
	<a class="item modalform" href="{{rooturl}}/user/edit_password" title="Passwort ändern">
		<h1>Passwort</h1>
		<p>Ändere dein Passwort</p>
	</a>
	<a class="item modalform" href="{{rooturl}}/user/edit_email" title="E-Mail ändern">
		<h1>E-Mail</h1>
		<p>Ändere deine E-Main Adresse</p>
	</a>
	<a class="item inactive">
		<h1>Freundschafts System</h1>
		<p>Wird einen Freund und sichere dir damit spezielle Vorteile</p>
	</a>
</div>
  <!-- USER DATA -->