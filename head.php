<div id="wrapper">
	<?php include("menu.php"); ?>
</div>
  
<div id="header">
	<object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="700" height="364">
		<param name="movie" value="images/cataclysm.swf" />
		<param name="quality" value="high" />
		<param name="wmode" value="opaque" />
		<param name="swfversion" value="8.0.35.0" />
		<!-- Dieses param-Tag fordert Benutzer von Flash Player 6.0 r65 und höher auf, die aktuelle Version von Flash Player herunterzuladen. Wenn Sie nicht wünschen, dass die Benutzer diese Aufforderung sehen, löschen Sie dieses Tag. -->
		<param name="expressinstall" value="Scripts/expressInstall.swf" />
		<!-- Das nächste Objekt-Tag ist für Nicht-IE-Browser vorgesehen. Blenden Sie es daher mit IECC in IE aus. -->
		<!--[if !IE]>-->
		<object type="application/x-shockwave-flash" data="images/cataclysm.swf" width="700" height="364">
		<!--<![endif]-->
		<param name="quality" value="high" />
		<param name="wmode" value="opaque" />
		<param name="swfversion" value="8.0.35.0" />
		<param name="expressinstall" value="Scripts/expressInstall.swf" />
		<!-- Im Browser wird für Benutzer von Flash Player 6.0 und älteren Versionen der folgende alternative Inhalt angezeigt. -->
		<div>
  		<h4>Für den Inhalt dieser Seite ist eine neuere Version von Adobe Flash Player erforderlich.</h4>
  		<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Adobe Flash Player herunterladen" width="112" height="33" /></a></p>
		</div>
		<!--[if !IE]>-->
		</object>
		<!--<![endif]-->
	</object>
          
	<?php include(dirname(__FILE__) . "/form_login.php") ?>

	<!-- INFO BOX -->
	<?php
 		if(isset($_REQUEST['l'])){
  		echo "<div id=\"infobox\">logout erfolgreich!</div>";
  	}
	?>
</div><!-- END OF HEADER -->

<div id="seperator"></div>