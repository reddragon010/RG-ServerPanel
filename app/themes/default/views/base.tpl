{% set flash = flushflash() %}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="de">  
  <head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" media="screen" href="{{themeurl}}/css/style.css">
		<link rel="stylesheet" type="text/css" media="screen" href="{{themeurl}}/css/ui-darkness/jquery-ui-1.8.9.custom.css">
		<link rel="stylesheet" type="text/css" media="screen" href="{{themeurl}}/css/jquery.jnotify.css">
		<script src="{{themeurl}}/js/jquery-1.4.4.min.js" type="text/javascript"></script>
		<script src="{{themeurl}}/js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
		<script src="{{themeurl}}/js/jquery.jnotify.js" type="text/javascript"></script>
		<script src="{{themeurl}}/js/jquery-form-function.js" type="text/javascript"></script>
		<script src="{{themeurl}}/js/functions.js" type="text/javascript"></script>
	  <title>{% block title %}RG-ServerPanel{% endblock %}</title>
		{% block head %}{% endblock %}
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<h2>RG-ServerPanel</h2>
			</div>
			<div id="navigation">
				<ul>
					<li><a href="#">Users</a>
						<ul>
							<li><a href="#">Search</a></li>
							<li><a href="#">Lockdown</a></li>
						</ul>
					</li>
					<li><a href="#">Accounts</a>
						<ul>
							<li><a href="#">Search</a></li>
							<li><a href="#">Banned</a></li>
						</ul>
					</li>
					<li><a href="#">Characters</a>
						<ul>
							<li><a href="#">Search</a></li>
							<li><a href="#">Move</a></li>
						</ul>
					</li>
					<li><a href="#">Guilds</a>
						<ul>
							<li><a href="#">Search</a></li>
							<li><a href="#">Rename</a></li>
						</ul>
					</li>
					<li><a href="#">Premium-Codes</a>
						<ul>
							<li><a href="#">Search</a></li>
						</ul>
					</li>
					
			</div>
			<div id="notifications"></div>
			<div id="content">
				{% block content %}{% endblock %}
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
       
		{% block foot %}{% endblock %}
	</body>
</html>