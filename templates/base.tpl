{% set flash = flushflash() %}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="de">  
  <head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/ui-darkness/jquery-ui-1.8.9.custom.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/jquery.jnotify.css">
    <script src="js/check.js" type="text/javascript"></script>
    <script src="js/swfobject_modified.js" type="text/javascript"></script>
		<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
		<script src="js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
		<script src="js/jquery.jnotify.js" type="text/javascript"></script>
		<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
	  <title>Rising-Gods TBC</title>
  </head>
    
  <body align="center">
    <div id="wrapper">
      
      <div id="page">
      
      	<div id="menu">
            	
<div id="menurechts">
                <div id="Tabelle_03">
                    <div id="menurechts-01" width="193" height="124"></div>
                    <div id="menurechts-02" width="42" height="61"></div>
                    <div id="menurechts-data" width="117" height="48">
                    	{{ getServerStatus() }}
                        <div id="menurechts-data-tiny">
							{{ getPlayersOnlineCount() }}
                       	  <br />
                          	{{ getServerUptime() }}
                      </div>
                    </div>
                    
      <div id="menurechts-04" width="34" height="61"></div>
                    <div id="menurechts-05" width="117" height="13"></div>
                </div>
            </div>
            
        </div>
        <div id="notifications"></div>
              
              <!--[if !IE]>-->
              <param name="wmode" value="opaque" />
              <!--<![endif]-->
              <!--[if IE]>-->
              <param name="wmode" value="transparent" />
              <!--<![endif]-->
              
        <div id="header">
            
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="455" height="207" align="right" id="FlashID">
              <param name="movie" value="../flash/wrath.swf" />
              <param name="quality" value="high" />
              <param name="wmode" value="opaque" />
              <param name="swfversion" value="8.0.35.0" />
              <!-- Dieses param-Tag fordert Benutzer von Flash Player 6.0 r65 und höher auf, die aktuelle Version von Flash Player herunterzuladen. Wenn Sie nicht wünschen, dass die Benutzer diese Aufforderung sehen, löschen Sie dieses Tag. -->
              <param name="expressinstall" value="Scripts/expressInstall.swf" />
              <!-- Das nächste Objekt-Tag ist für Nicht-IE-Browser vorgesehen. Blenden Sie es daher mit IECC in IE aus. -->
              <!--[if !IE]>-->
              <object data="../flash/wrath.swf" type="application/x-shockwave-flash" width="455" height="207" align="right">
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
{% if user.logged_in %}
                {% include 'user_box.tpl' %}
            {% else %}
                {% include 'login_form.tpl' %}
        {% endif %} </div>
        <!-- END OF HEADER -->
            
    	<div id="container">
				{% if logged_in() %}
				<div id="register" style="visibility:visible;">
					{% include form_register.php %}
				</div>
				{% endif %}
    		{% block content %}{% endblock %}
    	</div>
      
      <div id="footer">
          <font size="-1">CopyRight Rising-Gods</font>    
      </div>
       
      </div>
    </div>
			
		{% if flash %}
		<script language="JavaScript">
		$(document).ready(function() {
			$('#notifications').jnotifyInizialize({
        oneAtTime: true
    	})

		  $('#notifications').jnotifyAddMessage({
				text: '{{ flash.msg }}',
				{% if flash.type == 'error' %}
				permanent: true,
		    type: 'error'	
				{% else %}
		    permanent: false
				{% endif %}
		  });
		});
		</script>
		{% endif %}
        <script type="text/javascript">
<!--
swfobject.registerObject("FlashID");
//-->
        </script>
</body>
  
</html>