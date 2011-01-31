<?php
require_once(dirname(__FILE__) . '/include/common.inc.php');
require_once(dirname(__FILE__) . '/include/character.class.php');
require_once(dirname(__FILE__) . '/include/functions_visual.php');

if(logged_in()){
	$user->fetchChars();
	$main = $user->fetchMainChar();
?>
<table class="chars">
	<thead>
		<tr>
			<th class="narrow">Avatar</th>
			<th>Name</th>
			<th class="narrow">Level</th>
			<th>Money</th>
			<th class="narrow">Flags</th>
			<th class="narrow"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="header" colspan="6">Main</td>
		</tr>
		<tr>
			<td class="narrow"><img src="<?php echo display_avatar($main); ?>" /></td>
			<td><?php echo $main->data['name']; ?></td>
			<td class="narrow"><?php echo $main->data['level']; ?></td>
			<td><?php echo display_money($main->data['money']); ?></td>
			<td class="narrow"><?php echo $main->data['flags']; ?></td>
			<td></td>
		</tr>
		<tr>
			<td class="header" colspan="6">Chars</td>
		</tr>
		<?php foreach($user->chars as $char){ ?>
		<tr>
			<td class="narrow"><img src="<?php echo display_avatar($char); ?>" /></td>
			<td><?php echo $char->data['name']; ?></td>
			<td class="narrow"><?php echo $char->data['level']; ?></td>
			<td><?php echo display_money($char->data['money']); ?></td>
			<td class="narrow"><?php echo $char->data['flags']; ?></td>
			<td><a href="index.php?a=make_main&guid=<?php echo $char->guid ?>">MakeMain</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php } ?>