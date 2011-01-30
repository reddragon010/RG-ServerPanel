<?php
 
function getServerStatus($ip, $port){
  if (! $sock = @fsockopen($ip, $port, $num, $error, 3)) 
    echo 'SERVER <font color="green">ON</font><br />';
  else{ 
    echo 'SERVER <font color="red">OFF</font><br />';
    fclose($sock); 
  }
}

function getPlayersOnline($host, $user, $pass, $db){
  
  $conn = mysql_connect($host, $user, $pass) or die('Connection failed: ' . mysql_error());
  mysql_select_db($db, $conn) or die('Select DB failed: ' . mysql_error());
 
  $sql = mysql_query("SELECT * FROM characters WHERE online='1' ORDER BY NAME") or die(mysql_error());
  return mysql_num_rows($sql);
  
}

function getPlayersOnlineCount($host, $user, $pass, $db){
  
  $conn = mysql_connect($host, $user, $pass) or die('Connection failed: ' . mysql_error());
  mysql_select_db($db, $conn) or die('Select DB failed: ' . mysql_error());
      
  $sql = "SELECT Count(Online) FROM `characters` WHERE `online` = 1";
  $result = mysql_query($sql, $conn);
  $row = mysql_fetch_array($result);
  $online = $row["Count(Online)"];
              
  echo 'Online Players: <b>' .$online.'</b>';
  
}

function getPlayersHordeOnlineCount($host, $user, $pass, $db){
  
  $conn = mysql_connect($host, $user, $pass) or die('Connection failed: ' . mysql_error());
  mysql_select_db($db, $conn) or die('Select DB failed: ' . mysql_error());
      
  $sql = "SELECT Count(Online) FROM `characters` WHERE `online` = 1 AND `race` IN (2, 5, 6, 8, 10)";
  $result = mysql_query($sql, $conn);
  $row = mysql_fetch_array($result);
  $onlinehorde = $row["Count(Online)"];
              
  echo 'Online Horde: <b>'.$onlinehorde.'</b>';
  
}

function getPlayersAllianzOnlineCount($host, $user, $pass, $db){
  
  $conn = mysql_connect($host, $user, $pass) or die('Connection failed: ' . mysql_error());
  mysql_select_db($db, $conn) or die('Select DB failed: ' . mysql_error());
      
  $sql = "SELECT Count(Online) FROM `characters` WHERE `online` = 1 AND `race` IN (1, 3, 4, 7, 11)";
  $result = mysql_query($sql, $conn);
  $row = mysql_fetch_array($result);
  $onlineally = $row["Count(Online)"];
              
  echo 'Online Allianz: <b> '.$onlineally.' </b>';
  
}

function getServerUptime($host, $user, $pass, $db){

  mysql_connect($host, $user, $pass) or die ("Can't connect with $host");
  mysql_selectdb ("$db");
  
  $sql = mysql_query ("SELECT * FROM $db.`uptime` ORDER BY `starttime` DESC LIMIT 1");  
  $uptime_results = mysql_fetch_array($sql);    
  
  if ($uptime_results['uptime'] > 86400) { 
      $uptime =  round(($uptime_results['uptime'] / 24 / 60 / 60),2)." Days";
  }
  elseif($uptime_results['uptime'] > 3600) { 
      $uptime =  round(($uptime_results['uptime'] / 60 / 60),2)." Hours";
  }
  else { 
      $uptime =  round(($uptime_results['uptime'] / 60),2)." Min";
  }
  
  echo "Uptime: <b>$uptime </b>";
  
}

function protect($string){
    $string = mysql_real_escape_string($string);
    $string = strip_tags($string);
    $string = addslashes($string);

    return $string;
}

function getNews($host, $user, $pass, $db){
	$conn = mysql_connect($host, $user, $pass) or die('Connection failed: ' . mysql_error());
	mysql_select_db($db, $conn) or die('Select DB failed: ' . mysql_error());
      
	$sql = "SELECT * FROM `news` ORDER BY `date` DESC;";
	$result = mysql_query($sql, $conn);
	
	$numrows = mysql_num_rows($result);
	
	if($numrows > 0){
		
		while($row = mysql_fetch_array($result)){;
		
			$id = $row["id"];
			$date = $row["date"];
			$title = $row["title"];
			$content = $row["content"];
			$author = $row["author"];
			#963
			echo "<font color=\"#963\">".$id."# ".$title.":</font><br />";
			echo "<font size=\"-1\">".$author.", ".$date."</font><br />";
			echo $content."<br /><br />";
		}
		
	}
}

function getRegistrationForm($self){
  if(!$_POST['submit']){ 
      echo '<b> Register </b> (Design in bearbeitung..)'; ?>
	  		
			<div id="fehlercodeRegister" style="color:red;">
            <?php
				if (isset ($_REQUEST["fehlerR"])){  
					echo $_REQUEST["fehlerR"];
				}
			?>
            </div>
            
        <?php
		echo '	
        <form method="post" id="form1" name="form1" onSubmit="return checkForm();" action="index.php">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr><th>Username</th><td><input class="logininputs" type="text" name="username"></td></tr>
            <tr><th>Password</th><td><input class="logininputs" type="password" name="password"></td></tr>
            <tr><th>Password<br /><span style="font-weight:100; font-size:8pt">Confirm</span></th>
            <td><input class="logininputs" type="password" name="passconf"></td></tr>
            <tr><th>E-Mail</th><td><input class="logininputs" type="text" name="email"></td></tr>
            <tr>
              <td></td>
			  <td align="right"><input type="submit" name="submit" value=".:: Register ::."></td>
            </tr>
          </table>
        </form>
        ';
  
  }else {
      $username = protect($_POST['username']);
      $password = protect($_POST['password']);
      $confirm = protect($_POST['passconf']);
      $email = protect($_POST['email']);
  	  $flags = "2";
  
      $errors = array();
  
      if(!$username){
          $errors[] = "<center><b>Username is not defined!</b></center>";
      }

      if(!$password){
          $errors[] = "<center><b>Please enter a Password</b></center>";
      }

      if($password){
          if(!$confirm){
              $errors[] = "<center><b>Please Confirm the Password.</b></center>";
          }
      }

      if(!$email){
          $errors[] = "<center><b>Please Enter your Email.</b></center>";
      }

      if($password && $confirm){
          if($password != $confirm){
              $errors[] = "<center><b>Passwords do not match!</b></center>";
          }
      }


      if($username){
          $sql = "SELECT * FROM `account` WHERE `username`='".$username."'";
          $res = mysql_query($sql) or die(mysql_error());

              if(mysql_num_rows($res) > 0){
                  $errors[] = "<center><b>The Username is already in use, Please try another Username.</b></center>";
              }
      }

      if($email){
          $sql2 = "SELECT * FROM `account` WHERE `email`='".$email."'";
          $res2 = mysql_query($sql2) or die(mysql_error());

              if(mysql_num_rows($res2) > 0){
                  $errors[] = "<center><b>That Email is Already in Use. Please try Another one.</b></center>";
              }

      }

      if(count($errors) > 0){
          foreach($errors AS $error){
              echo $error . "<br />";
          }
		  echo "<a href=\"http://78.46.85.239/test/index.php?b=1\"><font color=\"yellow\">Back</font></a>";
      }else {
          $password = sha1(strtoupper($username) . ":" . strtoupper($password));
          $sql4 = "INSERT INTO `account`
                  (`username`,`sha_pass_hash`,`email`,`expansion`)
                  VALUES ('".$username."','".$password."','".$email."','".$flags."')";
          $res4 = mysql_query($sql4) or die(mysql_error());
          echo "<strong><font align=\"center\"><br><br><center>Thank you for registering,<b> " . $_POST['username'] . "</b>!</font></strong></center><br>click <a href=\"http://78.46.85.239/test/index.php\"><font color=\"yellow\">here</font></a> for login";
      }
   }
}

?>