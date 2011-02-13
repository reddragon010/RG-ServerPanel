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
                    <div id="menu">
                    <table id="menutable" width="700" height="85" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <a href="index.php"
                                    onmouseover="window.status='Home'; home.src='themes/cata/images/menu_light_01.jpg';  return true;"
                                    onmouseout="window.status=''; home.src='themes/cata/images/menu_01.jpg';  return true;">
                                    <img src="themes/cata/images/menu_01.jpg" width="125" height="85" border="0" alt="Home" name="home"></a></td>
                            <td>
                                <a href="site_howto.php"
                                    onmouseover="window.status='HowToPlay'; howto.src='themes/cata/images/menu_light_02.jpg';  return true;"
                                    onmouseout="window.status=''; howto.src='themes/cata/images/menu_02.jpg';  return true;">
                                    <img src="themes/cata/images/menu_02.jpg" width="112" height="85" border="0" alt="HowTo" name="howto"></a></td>
                            <td>
                            
                            {% if user.logged_in %}
                            	<a href="#news"
                                onmouseover="window.status='News'; news.src='themes/cata/images/menu_light_news_03.jpg';  return true;"
                                    onmouseout="window.status=''; news.src='themes/cata/images/menu_news_03.jpg';  return true;">
                                    <img src="themes/cata/images/menu_news_03.jpg" width="113" height="85" border="0" alt="News" name="news">
                                </a>
                            {% else %}
                            	<a href="register.php" title="Register" form-height="300" form-width="350" class="modalform"
                                    onmouseover="window.status='Register'; register.src='themes/cata/images/menu_light_03.jpg';  return true;"
                                    onmouseout="window.status=''; register.src='themes/cata/images/menu_03.jpg';  return true;">
                                    <img src="themes/cata/images/menu_03.jpg" width="113" height="85" border="0" alt="Register" name="register">
								</a>
                            {% endif %}
                            
                            </td>
                            <td>
                                <a href="chars_online.php"
                                 onmouseover="window.status='CharsOnline'; charsonline.src='themes/cata/images/menu_light_04.jpg';  return true;"
                                    onmouseout="window.status=''; charsonline.src='themes/cata/images/menu_04.jpg';  return true;">
                                    <img src="themes/cata/images/menu_04.jpg" width="114" height="85" border="0" alt="Tracker" name="charsonline"></a></td>
                            <td>
                                <a href="#extras"
                                    onmouseover="window.status='Extras'; extras.src='themes/cata/images/menu_light_05.jpg';  return true;"
                                    onmouseout="window.status=''; extras.src='themes/cata/images/menu_05.jpg';  return true;">
                                    <img src="themes/cata/images/menu_05.jpg" width="112" height="85" border="0" alt="Extras" name="extras"></a></td>
                            <td>
                            
                            {% if user.logged_in %}
                            	<a href="logout.php" 
                                	onmouseover="window.status='Logout'; logoutx.src='themes/cata/images/menu_light_logout_06.jpg';  return true;"
                                    onmouseout="window.status=''; logoutx.src='themes/cata/images/menu_logout_06.jpg';  return true;">
                             <img src="themes/cata/images/menu_logout_06.jpg" width="124" height="85" border="0" alt="Logout" name="logoutx"></a>
                            {% else %}
                            	<a href="login.php" title="Login" form-height="250" form-width="300" class="modalform"
                                    onmouseover="window.status='Login'; login.src='themes/cata/images/menu_light_06.jpg';  return true;"
                                    onmouseout="window.status=''; login.src='themes/cata/images/menu_06.jpg';  return true;">
                                    <img src="themes/cata/images/menu_06.jpg" width="124" height="85" border="0" alt="Login" name="login"></a>
                            {% endif %}
                            
                            </td>
                            
                        </tr>
                    </table>
                    <div id="menulinks">
                        <div id="ketten"></div>
                        <ul id="menu1">
                            <li class="link1">
                                <a href="">Das Team</a>
                            </li>
                            <li>
                                <a href="">Bugtracker</a>
                            </li>
                            <li>
                                <a href="">Premium-System</a>
                            </li>
                            <li>
                                <a href="">Bugtracker</a>
                            </li>
                            <li class="endlink">
                                <a href="">Tools</a>
                            </li>
                        </ul>
                        <div id="menuseperator"></div>
                        <ul id="menu2">
                            <li class="link1">
                                <a href="">Das Team</a>
                            </li>
                            <li>
                                <a href="">Bugtracker</a>
                            </li>
                            <li>
                                <a href="">Extras</a>
                            </li>
                            <li class="endlink">
                                <a href="">Tools</a>
                            </li>
                        </ul>
                        <div id="menufooter"></div>
                    </div>
                    <!--
                    <div id="menurechts">
                        <div id="Tabelle_03">
                            <div id="menurechts-01">
                                <img src="themes/cata/images/menurechts_01.png" width="193" height="124" alt="">
                            </div>
                            <div id="menurechts-02">
                                <img src="themes/cata/images/menurechts_02.png" width="42" height="61" alt="">
                            </div>
                            <!--MENU_RIGHT
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
                             MENU_RIGHT 
                            <div id="menurechts-04">
                                <img src="themes/cata/images/menurechts_04.png" width="34" height="61" alt="">
                            </div>
                            <div id="menurechts-05">
                                <img src="themes/cata/images/menurechts_05.png" width="117" height="13" alt="">
                            </div>
                        </div>
                    </div>
                    -->
                    </div>
					<!-- /MENU -->
                    
					<!-- NOTIFICATIONS -->
		      <div id="notifications"></div>
		  		<!-- /NOTIFICATIONS -->
		   		<!-- HEADER -->
		      <div id="header">
              
              <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="700" height="364">
              <param name="movie" value="{{theme_url}}/flash/cataclysm.swf" />
              <param name="quality" value="high" />
              <!--[if !IE]>-->
              <param name="wmode" value="opaque" />
              <!--<![endif]-->
              <!--[if IE]>-->
              <param name="wmode" value="transparent" />
              <!--<![endif]-->
              
              <param name="swfversion" value="8.0.35.0" />
              <!-- Dieses param-Tag fordert Benutzer von Flash Player 6.0 r65 und höher auf, die aktuelle Version von Flash Player herunterzuladen. Wenn Sie nicht wünschen, dass die Benutzer diese Aufforderung sehen, löschen Sie dieses Tag. -->
              <param name="expressinstall" value="{{theme_url}}/flash/expressInstall.swf" />
              <!-- Das nächste Objekt-Tag ist für Nicht-IE-Browser vorgesehen. Blenden Sie es daher mit IECC in IE aus. -->
              <!--[if !IE]>-->
              <object type="application/x-shockwave-flash" data="{{theme_url}}/flash/cataclysm.swf" width="700" height="364">
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
						
		        {% if user.logged_in %}
                	<div id="user_box" onClick="$('#user_menu').slideToggle()">
		        		{% include 'user_box.tpl' %}
                    </div>
                    <br />
                    <br />
		        {% else %}
                        <!--
                        <a href="login.php" title="Login" form-height="250" form-width="300" class="modalform"><div id="login"></div></a>
                        <a href="register.php" title="Register" form-height="300" form-width="350" class="modalform"><div id="register"></div></a>                		-->
		        {% endif %}
					    
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