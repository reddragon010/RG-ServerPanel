<form action="{{root_url}}/boss/update" method="post" accept-charset="utf-8">
	<label for="name">Name</label>
	<input type="text" name="name" value="{{boss.name}}" id="name">
	
	<label for="name">Status</label><br />
	{{ selectArray('status', STATUS, boss.status) }}<br />
	
	<label for="test_start">Test Start <span style="font-size: 40%">(zB: 2011-02-16 15:00:00)</span></label>
	<input type="text" name="test_start" value="{{boss.test_start}}" id="test_start">
	
	<label for="test_end">Test Ende <span style="font-size: 40%">(zB: 2011-02-19 10:00:00)</span></label>
	<input type="text" name="test_end" value="{{boss.test_end}}" id="test_end">
	
	<label for="comment">comment</label>
	<input type="text" name="comment" value="{{boss.comment}}" id="comment">
	
	<input type="hidden" name="id" value="{{boss.id}}" id="id">
	<input type="hidden" name="instance_id" value="{{boss.instance_id}}" id="id">
	
	<input type="submit" value="&auml;ndern">
</form>