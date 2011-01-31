<?php
require_once (dirname(__FILE__) . '/include/common.inc.php');

if(isset($_GET['a'])){ 
	switch($_GET['a']){
		case 'my_characters': 
			$content='my_characters.php'; 
			break;
		case 'make_main': 
			$content='my_characters.php';
			if(isset($_GET['guid']) && $user->setMainChar($_GET['guid'])){
				$flash['success'] = "Main wurde geändert";
			} else {
			  $flash['error'] = "Main konnte nicht geändert werden";
			}
			break;
		default: 
			$content='home.php'; 
			break;
	}
} else {
	$content='home.php';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="de">  
  <head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/layout.css">
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
			<?php include("head.php"); ?>
			
	    <div id="container">
				<div id="notifications"></div>
				<?php
				if(isset($content)){ 
					include($content);
				}
				?>
	    </div>
      
	    <?php include("foot.php"); ?>
       
		</div>
		<script type="text/javascript">
		<!--
			swfobject.registerObject("FlashID");
		//-->
		</script>
	<?php if(isset($flash)){ ?>
		
		<script language="JavaScript">
		$(document).ready(function() {
			$('#notifications').jnotifyInizialize({
        oneAtTime: true
    	})

		  $('#notifications').jnotifyAddMessage({
				text: '<?php echo $flash['error'] ?>',
				<?php if(isset($flash['error'])){ ?>
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