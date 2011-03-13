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
	  <title>{% block title %}Rising-Gods Vanilla{% endblock %}</title>
		{% block head %}{% endblock %}
	</head>
	<body align="center">
    
		<!-- WRAPPER -->
	  <div id="wrapper">
				<!-- PAGE -->
	    	<div id="page">
            
            <!-- MENU -->
            <div id="menu">
                <ul class="menu_top">
                    <!--
                    <li><a href="index.php">Home</a></li>
                    <li><a href="site_howto.php">HowTo</a></li>
                    -->
                    {% if user.logged_in %}
                        <!--<li><a href="site_livestream.php">Livestream</a></li>-->
                    {% else %}
                        <li><a href="register.php" title="Register" form-height="300" form-width="350" class="modalform">Register</a></li>
                    {% endif %}
                    <!--
                    <li><a href="chars_online.php">Online Charakter</a></li>
                    <li><a href="tools.php">Tools</a></li>
                    -->
                    {% if user.logged_in %}
                    	<li><a href="logout.php">Logout</a></li>
                    {% else %}
                        <li><a href="login.php" title="Login" form-height="260" form-width="300" class="modalform">Login</a></li>
                    {% endif %}
                </ul>
            </div>
            <!-- /MENU -->
                
            <!-- NOTIFICATIONS -->
            <div id="notifications"></div>
            <!-- /NOTIFICATIONS -->
            
            <!-- HEADER -->
            <div id="header">
            
            </div>
            <!-- /HEADER -->
            
            <!-- container_top --> 
            <div id="container_top"></div>
            <!-- /container_top -->
            
            <!-- CONTAINER --> 
            <div id="content">
                {% block content %}{% endblock %}
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