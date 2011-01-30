<?php
session_start();

include ("check_session.php");  
require_once ('config.php');
require_once ('functions.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="de">  
  <head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <script src="check.js" type="text/javascript"></script>
    <script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
  <title>Rising-Gods TBC</title>
        
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
        </div>
        
        <div id="header">
        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="700" height="364" id="FlashID" title="catabg">
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
        
          <div id="onlinestatus">
              
              <?php getServerStatus($ip, $port); ?>
              
              <font size="-1"> 
              <?php getPlayersOnlineCount($host, $user, $pass, $mangoscharacters); ?>
            </font><br />
              
              <font size="-1">
              <?php getServerUptime($host, $user, $pass, $mangosrealm); ?>
              </font>
              
          </div>
              
        <div id="willkommen">
            <?php echo $_SESSION['username'];?>,
            <a href="logout.php"><font color="yellow">logout</font></a><br />
            
            <!-- USER DATA -->
            <div id="userdata">
               user-id:&nbsp; <?php echo $_SESSION['id']; ?> <br />
               gm-level:&nbsp;<font color="#CC0000"><?php echo $_SESSION['gmlevel']; ?></font><br />
            </div>
        </div>
              
      <!-- INFO BOX -->
      <?php
        if(isset($_REQUEST['l'])){
          echo "<div id=\"infobox\">logout erfolgreich!</div>";
        }
      ?>
              
    </div>
    <!-- END OF HEADER -->
    
    <div id="seperator">
    
		<?php 
            if (isset ($_REQUEST["fehler"])){
                ?>
                <div id="fehlercodeLogin">
                <?php
                echo "Benutzername/Passwort nicht existent oder inkorrekt!";
                ?>
                </div>
                <?php
            } 
        ?>
    
    </div>
            
    <div id="container">
          
          <div id="newstext" style="width:650px; margin-left:10px;">
		  	<?php getNews($host, $user, $pass, $website); ?>
          </div>
          
      <?php 
          if(getPlayersOnline($host, $user, $pass, $mangoscharacters) > 0 ){
          ?>
              
          <?php
          }else{
              echo '<h3>No players that are currently playing in ChronosWoW!</h3>';
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
  </body>
  
</html>