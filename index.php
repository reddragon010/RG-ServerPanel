<?php
require_once (dirname(__FILE__) . '/include/common.inc.php');
define('IN_THE_BOX', true);
include("router.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="de">  
  <head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/ui-darkness/jquery-ui-1.8.9.custom.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/jquery.jnotify.css">
    <script src="js/check.js" type="text/javascript"></script>
    <script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
		<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
		<script src="js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
		<script src="js/jquery.jnotify.js" type="text/javascript"></script>
		<title>Rising-Gods TBC</title>
    <script language="JavaScript">
		function registerOn(){
    		  document.getElementById('register').style.visibility="visible";
    			document.getElementById('login').style.visibility="hidden";
    	}
    	function registerOff(){
    		  document.getElementById('register').style.visibility="hidden";
    			document.getElementById('login').style.visibility="visible";
    	}
  	</script>
	</head>
    
  <body align="center">
    <div id="page">
      <div id="wrapper">
      
      	<div id="menu">
            <table id="Tabelle_01" width="700" height="85" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <a href="index.php"
                            onmouseover="window.status='Home'; home.src='images/menu_light_01.jpg';  return true;"
                            onmouseout="window.status=''; home.src='images/menu_01.jpg';  return true;">
                            <img src="images/menu_01.jpg" width="125" height="85" border="0" alt="Home" name="home"></a></td>
                    <td>
                        <a href="#login"
                            onmouseover="window.status='Login'; login.src='images/menu_light_02.jpg';  return true;"
                            onmouseout="window.status=''; login.src='images/menu_02.jpg';  return true;">
                            <img src="images/menu_02.jpg" width="112" height="85" border="0" alt="Login" name="login"></a></td>
                    <td>
                        <a href="#register"
                            onmouseover="window.status='Register'; register.src='images/menu_light_03.jpg';  return true;"
                            onmouseout="window.status=''; register.src='images/menu_03.jpg';  return true;">
                            <img src="images/menu_03.jpg" width="113" height="85" border="0" alt="Register" name="register"></a></td>
                    <td>
                        <a href="#tracker"
                            onmouseover="window.status='Tracker'; tracker.src='images/menu_light_04.jpg';  return true;"
                            onmouseout="window.status=''; tracker.src='images/menu_04.jpg';  return true;">
                            <img src="images/menu_04.jpg" width="114" height="85" border="0" alt="Tracker" name="tracker"></a></td>
                    <td>
                        <a href="#forum"
                            onmouseover="window.status='Forum'; forum.src='images/menu_light_05.jpg';  return true;"
                            onmouseout="window.status=''; forum.src='images/menu_05.jpg';  return true;">
                            <img src="images/menu_05.jpg" width="112" height="85" border="0" alt="Forum" name="forum"></a></td>
                    <td>
                        <a href="#tools"
                            onmouseover="window.status='Tools'; tools.src='images/menu_light_06.jpg';  return true;"
                            onmouseout="window.status=''; tools.src='images/menu_06.jpg';  return true;">
                            <img src="images/menu_06.jpg" width="124" height="85" border="0" alt="Tools" name="tools"></a></td>
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
            
            <div id="menurechts">
                <div id="Tabelle_03">
                    <div id="menurechts-01">
                        <img src="images/menurechts_01.png" width="193" height="124" alt="">
                    </div>
                    <div id="menurechts-02">
                        <img src="images/menurechts_02.png" width="42" height="61" alt="">
                    </div>
                    <div id="menurechts-data" width="117" height="48">
                    	<?php getServerStatus(); ?>
                        <div id="menurechts-data-tiny">
							<?php getPlayersOnlineCount(); ?>
                            <br />
                            <?php getServerUptime(); ?>
                        </div>
                    </div>
                    <div id="menurechts-04">
                        <img src="images/menurechts_04.png" width="34" height="61" alt="">
                    </div>
                    <div id="menurechts-05">
                        <img src="images/menurechts_05.png" width="117" height="13" alt="">
                    </div>
                </div>
            </div>
            
        </div>
        <div id="notifications"></div>
        <div id="header">
            <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="700" height="364">
              <param name="movie" value="images/cataclysm.swf" />
              <param name="quality" value="high" />
              <!--[if !IE]>-->
              <param name="wmode" value="opaque" />
              <!--<![endif]-->
              <!--[if IE]>-->
              <param name="wmode" value="transparent" />
              <!--<![endif]-->
              
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
                
		<?php include('form_login.php'); ?>
                    
    </div>
    <!-- END OF HEADER -->
    
	<div id="seperator"></div>
            
    <div id="container">
    	<?php
			if(isset($content)){ 
				include($content);
			}
			?>
    </div>
      
      <div id="footer">
          <font size="-1">CopyRight Rising-Gods</font>    
      </div>
       
      </div>
    </div>
  	<script type="text/javascript">
		<!--
		swfobject.registerObject("FlashID");
		//-->
    </script>
		<?php
		 	$flash = flushflash();
			if(!empty($flash)){ ?>

			<script language="JavaScript">
			$(document).ready(function() {
				$('#notifications').jnotifyInizialize({
	        oneAtTime: true
	    	})

			  $('#notifications').jnotifyAddMessage({
					text: '<?php echo $flash['msg'] ?>',
					<?php if($flash['type'] == 'error'){ ?>
					permanent: true,
			    type: 'error'	
					<?php } else { ?>
			    permanent: false
					<?php } ?>
			  });
			});
			</script>
		<?php } ?>
</body>
  
</html>