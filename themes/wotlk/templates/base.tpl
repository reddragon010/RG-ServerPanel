{% set flash = flushflash() %}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="de">  
  <head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" media="screen" href="{{theme_url}}/css/style.css">
		<link rel="stylesheet" type="text/css" media="screen" href="{{theme_url}}/css/ui-darkness/jquery-ui-1.8.9.custom.css">
		<link rel="stylesheet" type="text/css" media="screen" href="{{theme_url}}/css/jquery.jnotify.css">
    <script src="{{theme_url}}/js/check.js" type="text/javascript"></script>
		<script src="{{theme_url}}/js/jquery-1.4.4.min.js" type="text/javascript"></script>
		<script src="{{theme_url}}/js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
		<script src="{{theme_url}}/js/jquery.jnotify.js" type="text/javascript"></script>
    <script src="{{theme_url}}/js/swfobject_modified.js" type="text/javascript"></script>
		<script src="{{theme_url}}/js/jquery-form-function.js" type="text/javascript"></script>
		<script src="{{theme_url}}/js/functions.js" type="text/javascript"></script>
	  <title>{% block title %}Rising-Gods TBC{% endblock %}</title>
		{% block head %}{% endblock %}
	</head>
	<body align="center">
		<!-- WRAPPER -->
	  <div id="wrapper">
				<!-- PAGE -->
	    	<div id="page">

					<!-- MENU -->
                    <table id="menutable" width="700" height="49" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td rowspan="2" height="49" width="2">
                                <img id="menu_02" src="images/menu_02.jpg" width="2" height="49" alt="" /></td>
                            <td width="522" height="46" background="menu_bottom.jpg">
                            <div id="menu">
                                <ul>
                                    <li><a href="index.php">Home</a></li>
                                    <li><a href="site_howto.php">HowToPlay</a></li>
                                    <li><a href="chars_online.php">Online Chars</a></li>
                                </ul>
                            </div>
                            </td>
                            <td width="176" height="46">
                                <img id="menu_06" src="images/menu_06.jpg" width="176" height="46" alt="" /></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <img id="menu_08" src="images/menu_08.jpg" width="698" height="3" alt="" /></td>
                        </tr>
                    </table>
					<!-- /MENU -->
                    
					<!-- NOTIFICATIONS -->
		      <div id="notifications"></div>
		  		<!-- /NOTIFICATIONS -->
		   		<!-- HEADER -->
		      <div id="header">
		        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="395" height="207" align="right" id="FlashID">
		          <param name="movie" value="{{theme_url}}/flash/wrath.swf" />
		          <param name="quality" value="high" />
		          <param name="wmode" value="opaque" />
		          <param name="swfversion" value="8.0.35.0" />
		          <!-- Dieses param-Tag fordert Benutzer von Flash Player 6.0 r65 und höher auf, die aktuelle Version von Flash Player herunterzuladen. Wenn Sie nicht wünschen, dass die Benutzer diese Aufforderung sehen, löschen Sie dieses Tag. -->
		          <param name="expressinstall" value="{{theme_url}}/flash/expressInstall.swf" />
		          <!-- Das nächste Objekt-Tag ist für Nicht-IE-Browser vorgesehen. Blenden Sie es daher mit IECC in IE aus. -->
		          <!--[if !IE]>-->
		          <object data="flash/wrath.swf" type="application/x-shockwave-flash" width="395" height="207" align="right">
		            <!--<![endif]-->
		            <param name="quality" value="high" />
		            <param name="wmode" value="opaque" />
		            <param name="swfversion" value="8.0.35.0" />
		            <param name="expressinstall" value="{{theme_url}}/flash/expressInstall.swf" />
		            <!-- Im Browser wird für Benutzer von Flash Player 6.0 und älteren Versionen der folgende alternative Inhalt angezeigt. -->
		            <div>
		              <h4>Für den Inhalt dieser Seite ist eine neuere Version von Adobe Flash Player erforderlich.</h4>
		              <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Adobe Flash Player herunterladen" width="112" height="33" /></a></p>
		            </div>
		            <!--[if !IE]>-->
		          </object>
		          <!--<![endif]-->
		        </object>
		        <script type="text/javascript">
		        <!--
		        swfobject.registerObject("FlashID");
		        //-->
		        </script>
		      </div>
		      <!-- /HEADER -->
		      <!-- CONTAINER -->      
		    	<div id="container_bg">
                <div id="container">
						<div id="user_box" onClick="$('#user_menu').slideToggle()">
		        {% if user.logged_in %}
		        	{% include 'user_box.tpl' %}
		        {% else %}
							<a href="login.php" title="Login" form-height="250" form-width="300" class="modalform">Login</a>
							<a href="register.php" title="Register" form-height="300" form-width="350" class="modalform">Register</a>                
		        {% endif %}
						</div>
						<div id="content">
							{% block content %}{% endblock %}
						</div>
       
		      </div>
              </div>
					<!-- /CONTAINER -->
					<!-- FOOTER -->
					<div id="footer">
						
					</div>
					<!-- FOOTER -->
				</div>
				<!-- /PAGE -->
				<!-- MENU_RIGHT -->
				<div id="menurechts">
	      	{% for realm in realms %}
					<div id="realm-{{realm.id}}" class="realm">
						{{ realm.getStatus()|online }}
	        	<div id="menurechts-data-tiny">
							Online Char: {{ realm.getOnlineCharsCount() }}
	        		<br />
							Uptime: {{ realm.getUptime()|uptime }}
	        	</div>
					</div>
					{% endfor %}
		  	</div>
				<!-- /MENU_RIGHT -->
		</div>
		<!-- /WRAPPER -->	
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
		{% block foot %}{% endblock %}
	</body>
</html>