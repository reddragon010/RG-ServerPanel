<form action="{{rooturl}}/ticket/create" method="post" accept-charset="utf-8">
	<label style="display:inline">Char:</label>{{ selectArray('character_id', characters) }}
	<label style="display:inline">Realm:</label>{{ selectArray('realm_id', realms) }}
	<label style="display:inline">Kategorie:</label>{{ selectArray('category_id', categories) }}
	<label for="title">Titel</label><input type="text" name="title" value="">
	<label for="content">Anfrage</label><textarea rows="5" name="content" value=""></textarea>
	<input type="submit" value="Erstellen">
</form>