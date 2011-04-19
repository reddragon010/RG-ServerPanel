{% set flash = flushflash() %}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="de">  
  <head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" media="screen" href="{{themeurl}}/css/style.css">
		<link rel="stylesheet" type="text/css" media="screen" href="{{themeurl}}/css/ui-darkness/jquery-ui-1.8.9.custom.css">
		<link rel="stylesheet" type="text/css" media="screen" href="{{themeurl}}/css/jquery.jnotify.css">
    <script src="{{themeurl}}/js/check.js" type="text/javascript"></script>
		<script src="{{themeurl}}/js/jquery-1.4.4.min.js" type="text/javascript"></script>
		<script src="{{themeurl}}/js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
		<script src="{{themeurl}}/js/jquery.jnotify.js" type="text/javascript"></script>
    <script src="{{themeurl}}/js/swfobject_modified.js" type="text/javascript"></script>
		<script src="{{themeurl}}/js/jquery-form-function.js" type="text/javascript"></script>
		<script src="{{themeurl}}/js/functions.js" type="text/javascript"></script>
	  <title>{% block title %}Rising-Gods Beta{% endblock %}</title>
		{% block head %}{% endblock %}
	</head>
	<body>
		<div id="notifications"></div>
		<div id="content">
			{% block content %}{% endblock %}
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