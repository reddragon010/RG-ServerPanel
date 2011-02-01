<?php if(!logged_in()){ ?>    
<div id="register" style="visibility:visible;">
	<?php include(dirname(__FILE__) . "/form_register.php"); ?>
</div>
<?php } ?>
  
<div id="newstext" style="width:390px; margin-left:10px;">
	<?php getNews(); ?>
</div>
  
<?php if(getPlayersOnline() > 0 ){ 

	}else{
		echo '<h3>No players that are currently playing in ChronosWoW!</h3>';
	}
?>